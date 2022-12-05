@extends('layout')
@section('content')
<style>
  .push-top {
    margin-top: 50px;
  }
</style>
<div class="push-top">
  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}  
    </div><br/>
  @endif
  <div class="container mt-5">
        <div classs="form-group">
            <input type="text" id="search" name="search" placeholder="Enter some thing to search" onkeyup="searchByKwd()" class="form-control" />
        </div>
        
        <!-- AJax Search Resul -->
        <div id='searchRs'></div>
        <p></p>
    </div>
    <div style="text-align:right; margin-bottom:3px">
    <a href="/social-media-share" class="btn btn-primary btn-sm"> <i class="bi bi-share-fill"></i> SharePage</a>
    <a href="/home-upload" class="btn btn-primary btn-sm"> <i class="bi bi-images"></i> Files</a>
    
        <a href="{{ route('students.create')}}" class="btn btn-primary btn-sm"> <i class="bi bi-plus-circle"></i> Ajouter</a>
        <p></p>
    </div>
  <table class="table">
    <thead>
        <tr class="table-warning">
          <td>ID</td>
          <td>Name</td>
          <td>Email</td>
          <td>Phone</td>
          <td>Password</td>
          <td class="text-center">Action</td>
        </tr>
    </thead>
    <tbody>
        @foreach($student as $students)
        <tr>
            <td>{{$students->id}}</td>
            <td>{{$students->name}}</td>
            <td>{{$students->email}}</td>
            <td>{{$students->phone}}</td>
            <td>{{$students->password}}</td>
            <td class="text-center">
                <a href="{{ route('students.edit', $students->id)}}" class="btn btn-primary btn-sm"">Edit</a>
                <form action="{{ route('students.destroy', $students->id)}}" method="post" style="display: inline-block">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm"" type="submit">Delete</button>
                  </form>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
<div>

<script>
  document.addEventListener("DOMContentLoaded",function(){
    //Using typeHead JS
       var route = "{{ url('autocomplete-search') }}";
        $('#search').typeahead({
            source: function (query, process) {
                return $.get(route, {
                    query: query
                }, function (data) {
                    return process(data);
                });
            }
        });
  });

  function searchByKwd(){
    //Using Ajax
  console.log("... Searching Data >>>");
   let kwd = document.getElementById('search');   
   let searchRs  = document.getElementById('searchRs');  
   let url = "{{ url('autocomplete-search') }}"+"?query="+kwd.value;
        fetch(url, {
            method: 'GET',
                headers: {
                    "Content-Type": "application/json"
                },
                cache: 'default'})
        .then((response) => {
            if (response.status === 200) {
                const contentType = response.headers.get("content-type");
                if (contentType) {
                    response.json().then((json) => {
                        let addHTML ='';
                     if (json.length > 0) {
                            addHTML += `<div class="members allmembers"> <div class="members-title"> <h4> Search Result</h4> </div>`;		
                            addHTML += `<table  class="table">`;
                            addHTML += `<tr> <th style="width:51px"></th> <th> Nom</th> <th>Action</th> </tr>`;
                            for (let i = 0; i < json.length; i++) {
                               addHTML += `<tr> <td> <i class="bi bi-person-circle"></i> </td>`;
                                addHTML += `<td> ${json[i].name}</td>`;   
                                addHTML += `<td>  <a href="students/`+json[i].id+`/edit">Ouvrir</a> </td>`;                               
                                addHTML += `</tr>`;
                            }
                            addHTML += '</table>';
                            searchRs.innerHTML = addHTML;
                                // Reset MessageLabel
                             labelMessage.innerHTML = " ";
                        } else {
                            // Reset searchRs Model
                            searchRs.innerHTML = "No Data founded";
                            console.log("...No Data founded");
                        }

                    })
                }
            }  
        })
        .catch((err) => {            
            console.error(err)
            })
    }
</script>
@endsection

