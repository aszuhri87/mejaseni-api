<?php

/**
 * @author mtaufiikh@gmail.com
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:table {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Table With Versioning';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if(!$this->argument('table')){
            $this->info('argument is required');
            return;
        }

        $table_name = $this->argument('table');

        $rev = 1;
        $files = scandir(base_path("database/migrations"));
        foreach ($files as $file) {
            if (strstr($file, "update_{$table_name}_table_rev{$rev}")) {
                $rev++;
            }
        }

        $buffer = file_get_contents(base_path('examples/UpdateMigration.php'));

        $class_name = Str::camel("update_{$table_name}_table_rev{$rev}");
        $class_name[0] = strtoupper($class_name[0]);

        $buffer = str_replace('ClassName', $class_name, $buffer);

        $buffer = str_replace('table_name', $table_name, $buffer);

        $file_name = date('Y_m_d_His')."_update_{$table_name}_table_rev{$rev}";

        file_put_contents(base_path("database/migrations/{$file_name}.php"), $buffer);

        $this->info('Successfully create Update Migration.');
    }
}
