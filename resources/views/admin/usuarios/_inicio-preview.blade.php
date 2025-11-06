

        <form action="#">
        @csrf 
                              

                    <div class="row mt-4 mb-3">
                            <div class="col-12 col-sm-5">
                                <label for="">Nome * </label>
                                <input type="text"  name="name" disabled required class="form-control" value="{{$usuario->name}}">
                               
                            </div>
                            <div class="col-12 col-sm-4 ">
                                 <label for="">Email *</label>
                                <input type="email" name="email" id="email" disabled class="form-control" value="{{$usuario->email}}" required>
                            </div>   

                            <div class="col-12 col-sm-2">
                                <label for="">Perfil * </label>
                                <input type="email" name="role" id="role" disabled class="form-control" value="{{$usuario->role}}" required>

                               
                            </div>
                           
                     </div>
                    
        </form>

