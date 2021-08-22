<div class="users" style="padding: 10px" >
    <h2>Add Users</h2>
        <form action="/role-management/users/create" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="form-group">
                <label for="recipient-name" class="col-form-label">Name:</label>
                <input type="text" class="form-control" id="recipient-name" name="name" required>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="col-form-label">User Email:</label>
                <input type="email" class="form-control" id="recipient-name" name="email" required>
            </div>
            <div class="form-group">
                <label for="inputState">User Role</label>
                <select id="inputState" class="form-control" name="role_id" required>
                    <option selected>Choose...</option>
                    @foreach($roles as $role)
                    <option value="{{$role->id}}">{{$role->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="col-form-label"> User Name:</label>
                <input type="text" class="form-control" id="recipient-name" name="username" required>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="col-form-label">User Password:</label>
                <input type="password" class="form-control" id="recipient-name" name="password" required>
            </div>
            <div class="form-group">
                <label for="inputState">User Status</label>
                <select id="inputState" class="form-control" name="status" required>
                    <option selected>Choose...</option>
                    <option value="1">Active</option>
                    <option value="0">De-active</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Sign in</button>
            <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
        </form>
</div>