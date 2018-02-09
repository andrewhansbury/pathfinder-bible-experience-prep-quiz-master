<?php


use Phinx\Migration\AbstractMigration;

class CreateUsers extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('Users', ['id' => 'UserID']);
        $table->addColumn('Username', 'string', ['limit' => 150]);
        $table->addColumn('EntryCode', 'string', ['limit' => 25]);
        $table->addColumn('Password', 'string', ['limit' => 250]);
        $table->addColumn('LastLoginDate', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
        $table->addColumn('UserTypeID', 'integer', ['null' => true]);
        $table->addColumn('ClubID', 'integer');
        $table->addColumn('CreatedByID', 'integer', ['null' => true]);
        $table->addForeignKey('UserTypeID', 'UserTypes', 'UserTypeID', ['delete'=> 'SET_NULL', 'update' => 'NO_ACTION']);
        $table->addForeignKey('ClubID', 'Clubs', 'ClubID', ['delete'=> 'CASCADE', 'update' => 'NO_ACTION']);
        $table->addForeignKey('CreatedByID', 'Users', 'UserID', ['delete'=> 'SET_NULL', 'update' => 'NO_ACTION']);
        $table->create();
    }
}