<?php

namespace App\Tests\Controller;

use App\Repository\BoardRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BoardControllerTest extends WebTestCase
{
    public function testIndexPageLoadsSuccessfully(): void
    {
        $client = static::createClient();
        $client->request('GET', '/board/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form[action="/board/new"]');
    }

    public function testCreateBoardSuccessfully(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/board/');

        // given
        $randomUuid = Uuid::uuid4();
        $tableName = $randomUuid->toString();

        $form = $crawler->selectButton('board[save]')->form([
            'board[name]' => $tableName,
        ]);

        // when
        $client->submit($form);

        // then
        $this->assertResponseRedirects('/board/');

        $client->followRedirect();

        $boardRepository = static::getContainer()->get(BoardRepository::class);
        $board = $boardRepository->findOneBy(['name' => $tableName]);

        $this->assertNotNull($board);
    }
}
