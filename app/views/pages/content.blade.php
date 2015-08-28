@extends('layouts.default')
@section('title')
  Question
@stop
@section('content')
<div id="wrapper">
    
    <section id="top_area">
        
        <article class="box-right">
        
                <form  action="/add_question" method="post">
                
                    <table>
                        <tr>
                        <td> Question Title </td>
                        <td> <input name="question_title" required="required" placeholder="Question Title" type="text"></td>
                        </tr>
                                      <tr></tr>

                        <tr>
                        <td> Question </td>
                        <td> <textarea name="question" required="required" placeholder="Please type question here"></textarea> </td>
                        </tr>
                    
                        <tr>
                            <td></td>
                          <td>
                         <input value="Submit" type="submit"> &nbsp &nbsp
                         <input value="Reset" type="reset">
                          </td>
                        
                        </tr>

                  </table>      
                              
    			</form>

        </article>
    
    </section>

</div>
@stop