<?php

namespace App\Tests;

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
        $this->assertSelectorTextContains('.lg\:col-span-3 h2', 'Twoje aktywne tablice');
        $this->assertSelectorExists('form[action="/board/new"]');
    }

    public function testCreateBoardSuccessfully(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/board/');

        $randomUuid = Uuid::uuid4();
        $tableName = $randomUuid->toString();

        $form = $crawler->selectButton('board[save]')->form([
            'board[name]' => $tableName,
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/board/');

        $client->followRedirect();

        $this->assertSelectorTextContains('.bg-emerald-50', 'Nowa tablica została utworzona');

        $this->assertSelectorTextContains('h3', $tableName);

        $boardRepository = static::getContainer()->get(BoardRepository::class);
        $board = $boardRepository->findOneBy(['name' => $tableName]);

        $this->assertNotNull($board);
    }
}
