<?php

declare(strict_types=1);

namespace App\Tests\Unit\Reservations\Reservation\Application\Query;

use LastReservation\Reservations\Table\Application\Query\SearchTables;
use LastReservation\Reservations\Table\Application\Query\SearchTablesHandler;
use LastReservation\Reservations\Table\Application\Query\TableView;
use LastReservation\Reservations\Table\Application\Query\TableViewAssembler;
use LastReservation\Reservations\Table\Infrastructure\Persistence\TableInMemoryRepository;
use Monolog\Test\TestCase;

final class SearchTablesHandlerTest extends TestCase
{
    private TableInMemoryRepository $repository; //TODO: Mock this
    private SearchTablesHandler $handler;
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new TableInMemoryRepository();
        $this->handler = new SearchTablesHandler(
            $this->repository,
            new TableViewAssembler(),
        );
    }

    /**
     * TODO: Add tests to check that it fails when it should. Not only test the happy path
     */
    public function test_returns_an_array_of_table_views(): void
    {
        $tableViews = $this->handler->__invoke(
            new SearchTables(restaurantId: '123')
        );

        $this->assertInstanceOf(TableView::class, $tableViews[0]);
    }
}