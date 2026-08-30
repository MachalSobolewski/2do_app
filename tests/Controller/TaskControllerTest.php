<?php

namespace App\Tests\Controller;

use App\Entity\Board;
use App\Entity\BoardColumn;
use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $em;
    private BoardColumn $columnStart;
    private BoardColumn $columnTarget;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->em = static::getContainer()->get(EntityManagerInterface::class);

        $this->mockData();
    }

    public function testCreateTask(): void
    {
        // given
        $name = Uuid::uuid4()->toString();
        $description = 'Test description';

        // when
        $this->client->request(
            'POST',
            sprintf('/column/%d/task/new', $this->columnStart->getId()),
            [
                'task' => [
                    'name' => $name,
                    'description' => $description,
                ],
            ],
            [],
            ['HTTP_X-Requested-With' => 'XMLHttpRequest']
        );

        // then
        $this->assertResponseIsSuccessful();

        $task = $this->em->getRepository(Task::class)->findOneBy(['name' => $name]);
        $this->assertNotNull($task);
        $this->assertSame($this->columnStart->getId(), $task->getBoardColumn()->getId());
        $this->assertSame($description, $task->getDescription());
    }

    public function testMoveTaskToAnotherColumn(): void
    {
        // given
        $task = new Task();
        $task->setName('Test task')
            ->setDescription('Test description')
            ->setBoardColumn($this->columnStart);
        $this->em->persist($task);
        $this->em->flush();

        // when
        $this->client->request(
            'POST',
            sprintf('/task/%d/move-to/%d', $task->getId(), $this->columnTarget->getId()),
            [],
            [],
            ['HTTP_X-Requested-With' => 'XMLHttpRequest']
        );

        // then
        $this->assertResponseIsSuccessful();

        $this->em->refresh($task);
        $this->assertSame($this->columnTarget->getId(), $task->getBoardColumn()->getId());
    }

    private function mockData(): void
    {
        $board = new Board();
        $board->setName('Test Task PhpUnit');
        $this->em->persist($board);

        $this->columnStart = new BoardColumn();
        $this->columnStart
            ->setName('Column Start')
            ->setBoard($board)
            ->setPosition(0);
        $this->em->persist($this->columnStart);

        $this->columnTarget = new BoardColumn();
        $this->columnTarget
            ->setName('Column Target')
            ->setBoard($board)
            ->setPosition(1);
        $this->em->persist($this->columnTarget);

        $this->em->flush();
    }
}
