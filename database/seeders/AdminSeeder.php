namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'mugisha',
            'email' => 'mugisha@mighty.biz',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);
    }
}
