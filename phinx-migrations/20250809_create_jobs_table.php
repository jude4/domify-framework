<?php
declare(strict_types=1);
use Phinx\Migration\AbstractMigration;
final class CreateJobsTable extends AbstractMigration { public function change(): void { $table = $this->table('jobs'); $table->addColumn('queue','string',['limit'=>255])->addColumn('payload','text')->addColumn('attempts','integer',['default'=>0])->addColumn('reserved_at','integer',['null'=>true])->addColumn('available_at','integer',['default'=>0])->create(); } }
