<?php

namespace App\Tests\Controller;

use App\Entity\Board;
use App\Entity\BoardColumn;
use App\Entity\Task;
use App\Repository\BoardRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    public function testDeleteBoard(): void
    {
        $client = static::createClient();
        $em = static::getContainer()->get(EntityManagerInterface::class);

        // given
        $board = new Board();
        $board->setName('Table to Delete');
        $em->persist($board);

        $column = new BoardColumn();
        $column
            ->setName('Test Column')
            ->setPosition(0)
            ->setBoard($board);
        $em->persist($column);

        $task = new Task();
        $task
            ->setName('Test task')
            ->setDescription('Test description')
            ->setBoardColumn($column);
        $em->persist($task);

        $em->flush();

        $boardId = $board->getId();

        // when
        $client->request('POST', sprintf('/board/%d/delete', $boardId));

        // then
        $this->assertResponseRedirects('/board/');
        $client->followRedirect();

        $deletedBoard = $em->getRepository(Board::class)->find($boardId);
        $this->assertNull($deletedBoard);
    }
}
