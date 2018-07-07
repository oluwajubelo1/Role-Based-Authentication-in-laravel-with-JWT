<?php


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

       DB::table('users')->delete();
       $users=[
           ['name'=>'Ige Oluwasegun','email'=>'igeoluwasegun363@gmail.com','password'=>Hash::make('secret')],
           ['name'=>'Ibukun Afolabi','email'=>'afolabi@gmail.com','password'=>Hash::make('secret')],
           ['name'=>'Egbeyemi Olumide','email'=>'olumide@gmail.com','password'=>Hash::make('secret')],
           ['name'=>'Oluyemi Imole','email'=>'imole@pass.ng','password'=>Hash::make('secret')]
       ];

       foreach ($users as $user){
         User::create($user);
       }
        \Illuminate\Database\Eloquent\Model::reguard();
        // $this->call(UsersTableSeeder::class);
    }
}
