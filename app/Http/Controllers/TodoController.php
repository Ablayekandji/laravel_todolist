<?php

namespace App\Http\Controllers;

use App\Notifications\TodoAffected;
use App\Todo;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class TodoController extends Controller
{
    //la lsite des utilisateurs
    public $users;

    public function __construct()
    {
        $this->users = User::getAllUsers();
    }
    /**
     * affecter une todo a un utilisateur
     * @param App\Todo $todo
     * @param App\User $user
     * @return \Illuminate\Http\Response
     */
    public function affectedto(Todo $todo, User $user)
    {
        # code...
        $todo->affectedTo_id = $user->id;
        $todo->affectedBy_id = Auth::user()->id;
        $todo->update();
        $user->notify(new TodoAffected($todo));
        return back();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        /* $datas = Todo::all()->reject(function ($todo) {
            # code...
            return $todo->done == 0;
        });*/
        //dd($datas);
        $userId = Auth::user()->id;
        $users = $this->users;
        $datas = Todo::where(['affectedTo_id' => $userId])->orderBy('id', 'desc')->paginate(10); //pour paginer par liste de 10 pour chaque page
        return view('todos.index', compact('datas', 'users'));
    }

    /**
     * pour les todos terminees
     */
    public function done()
    {
        # code...
        $datas = Todo::where('done', 1)->paginate(10);
        $users = $this->users;
        return view('todos.index', compact('datas', 'users'));
    }
    /**
     * pour les todos no terminees
     */
    public function undone()
    {
        # code...
        $datas = Todo::where('done', 0)->paginate(10);
        $users = $this->users;
        return view('todos.index', compact('datas', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('todos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $todo = new Todo();
        $todo->creator_id = Auth::user()->id;
        $todo->affectedTo_id = Auth::user()->id;
        $todo->name = $request->name;
        $todo->description = $request->description;
        $todo->save();
        notify()->success("La todo <span class='badge badge-dark'>#$todo->id </span> vient d'etre créée");

        return redirect()->route('todos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function edit(Todo $todo)
    {
        //
        return view('todos.edit', compact('todo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Todo  $tod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        //
        if (!isset($request->done)) {
            $request['done'] = 0;
        }

        $todo->update($request->all());
        notify()->success("La todo <span class='badge badge-dark'>#$todo->id </span> a bien été mis a jour");
        return redirect()->route('todos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        //
        $todo->delete();
        notify()->error("La todo <span class='badge badge-dark'>#$todo->id </span>a bien été supprimée");
        return back();
    }

    /**
     * changer le status terminer en non terminer
     * @param Todo $todo
     * @return void
     */
    public function makedone(Todo $todo)
    {
        # code...
        $todo->done = 1;
        $todo->update();
        notify()->success("La todo <span class='badge badge-dark'>#$todo->id </span>a bien été terminée");
        return back();
    }
    /**
     * changer le status non terminer en  terminer
     * @param Todo $todo
     * @return void
     */
    public function makeundone(Todo $todo)
    {
        # code...
        $todo->done = 0;
        $todo->update();
        notify()->success("La todo <span class='badge badge-dark'>#$todo->id </span> est a nouveau ouverte");
        return back();
    }

    //¥
}
