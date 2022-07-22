<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* come ritorno avremo un array con le seguenti caratteristiche */
        $res = [
            'data' => [],
            'message' => '',
            'success' => true,
        ];
        try{
            $res['data'] =  User::orderBy('id', 'DESC')->get();// get riitorna array di oggetti (collection) orderBy posso agire sul query builder (la sua costruzione)
        }catch(\Exception $e){
            $res['message'] = $e->getMessage();
            $res['success'] = false;
        }
        return $res;
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) // creazione utente
    {
          /* come ritorno avremo un array con le seguenti caratteristiche */
          $res = [
            'data' => [],
            'message' => 'User Created',
            'success' => true,
        ];
        try{
            $userData = $request->except('id'); //ci prendiamo i dati tranne id perchè l'aravel lo crea in automatico
            /* in caso di passwod verifica se è stata inserita */
            /* $userData['password'] = $userData['passsword'] ?? 'dedede'; se è null metti pas di default 
            $userData['password'] = Hash::make($userData['password']; e hashiamo */
            /*// in caso di passwod verifica se è stata inserita */

            $user = new User();
           /*  popolare uno ad uno
            $user->name = $userData['name']; */
            /* input per leggere il valore dell'input */
            /* $user->lastname = $request->input('lastname'); */
            /* popolare tutti i campi */
            $user->fill($userData);
            $user->save();
            $res['data'] = $user;
        }catch(\Exception $e){
            $res['message'] = $e->getMessage();
            $res['success'] = false;
        }
        return $res;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     *///togliamo la tipificazione per gestire noi il try and catch
    //public function show(User $user)
    public function show($user)
    {
         /* come ritorno avremo un array con le seguenti caratteristiche */
         $res = [
            'data' => [],
            'message' => '',
            'success' => true,
        ];
        try{
            $res['data'] = User::findOrFail($user);
        }catch(\Exception $e){
            $res['message'] = $e->getMessage();
            $res['success'] = false;

        }
        return $res;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, /* User */int $user) /* edit dati utente verbo PATCH pochi dati PUT tutti i dati */
    {   
        /* leggere solo i dati che ci interessano */
        $data = $request->except(['id']);

        /* come ritorno avremo un array con le seguenti caratteristiche */
        $res = [
            'data' => [],
            'message' => '',
            'success' => true,
        ];
        /* VERIFICARE SE ID C'è O NO GESTENDO NOI L'ERRORE togliendo nei parametri USER e inseriamo int */
        try {
            $user = User::findOrFail($user);
            /* gestione password inserire breeze per auth e inserire la password come campo nell'intetro progetto Inteface, Users, laravel table model ecc..*/
            // $data['password'] = Hash::make($data['password']);
            /* /gestione password */

            /* se la find or fail ha successo si va a fare update dei dati */
            $user->update($data);
            /* in modo da avere i nuovi valori di user */
            $res['data'] = $user;
            /* messaggio per utente */
            $res['message'] ='User update';
        }catch(\Exception $e){
            $res['message'] = $e->getMessage(); /* messagio per user */
            $res['success'] = false;/* per verificare se beckend funziona */

        }
        return $res;
        /* aggiornare file KERNEL + api.php per middleware */
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $res = [
            'data' => [],
            'message' => 'User' . $user->id .' deleted',
            'success' => true,
        ];
        try{
            $res['success'] = $user->delete();
            /* verifichiamo se non viene eliminato per qualche ragione */
            if(!$res['success']){
                $res['message'] = 'Could not delete user' + $user->id;
            }
        }catch(\Exception $e){
            $res['message'] = $e->getMessage();
            $res['success'] = false;

        }
        return $res;
    }
}
