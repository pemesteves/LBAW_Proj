<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller{

    public function show(){
        if (!Auth::check()) return redirect('/login');

        $posts = array( 
                ['id' => '0', 'author' => 'Peter' , 'uni' => 'FEUP' , 'date' => "07-03-2020" , 'hour' => "14:30" , 'title' => "Impressão na FEUP" , 
                'post_content' => "Hoje de manhã estava na faculdade a tentar imprimir os conteúdos de PPIN e reparei que a impressora não reconhecia a minha pen. 
                Depois tentei enviar pelo FEUP WebPrint e também não recebeu nenhum ficheiro.
                Estamos sem sistema de impressão na faculdade?",'likes' => 2 , 'dislikes' => 1 , 
                'comments' => [ 
                        ['padding_left' => '0' , 'author' => "Joaquin" , 'comment' => "Talvez alguém nos vá informar através de email? Mas eu suponho que vamos ter aulas até mais tarde."],
                        ['padding_left' => '5em' , 'author' => "Tiago" , 'comment' => "Sim, já contactei vários professores e todos disseram que vai sair um comunicado oficial da faculdade."], 
                    ] 
                ] ,
                ['id' => '2', 'author' => 'Mary' , 'uni' => 'ICBAS' , 'date' => "07-03-2020" , 'hour' => "14:30" , 'title' => "Impressão no ICBAS" , 
                'post_content' => "Hoje de manhã estava na faculdade a tentar imprimir os conteúdos de PPIN e reparei que a impressora não reconhecia a minha pen. 
                Depois tentei enviar pelo FEUP WebPrint e também não recebeu nenhum ficheiro.
                Estamos sem sistema de impressão na faculdade?",'likes' => 2 , 'dislikes' => 1 , 
                'comments' => [ 
                        ['padding_left' => '0' , 'author' => "Joaquin" , 'comment' => "Talvez alguém nos vá informar através de email? Mas eu suponho que vamos ter aulas até mais tarde."],
                        ['padding_left' => '5em' , 'author' => "Tiago" , 'comment' => "Sim, já contactei vários professores e todos disseram que vai sair um comunicado oficial da faculdade."], 
                    ] 
                ]
        );


        return view('pages.feed' , ['is_admin' => false , 'posts' => $posts ]);

    }


}