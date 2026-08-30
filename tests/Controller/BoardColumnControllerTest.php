<?php

namespace App\Tests\Controller;

use App\Entity\Board;
use App\Entity\BoardColumn;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BoardColumnControllerTest extends WebTestCase
{
    public function testCreateColumn(): void
    {
        $client = static::createClient();
        /** @var EntityManagerInterface $em */
        $em = static::getContainer()->get(EntityManagerInterface::class);

        // given
        $board = new Board();
        $board->setName('Test Creation Column PhpUnit');
        $em->persist($board);
        $em->flush();

        $name = Uuid::uuid4();

        // when
        $client->request(
            'POST',
            sprintf('/board/%d/column/new', $board->getId()),
            ['board_column' => ['name' => $name]],
            [],
            ['HTTP_X-Requested-With' => 'XMLHttpRequest']
        );

        // then
        $this->assertResponseIsSuccessful();

        $column = $em->getRepository(BoardColumn::class)->findOneBy(['name' => $name]);
        $this->assertNotNull($column);
        $this->assertSame($board->getId(), $column->getBoard()->getId());
    }
}
