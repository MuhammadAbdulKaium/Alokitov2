<div class="users" style="padding: 10px" >
    <h2>Add Roles</h2>
        <form action="/role-management/role/create" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="form-group">
                <label for="recipient-name" class="col-form-label">Role Name</label>
                <input type="text" class="form-control" id="recipient-name" name="name" required>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="col-form-label">Display Name</label>
                <input type="text" class="form-control" id="recipient-name" name="display_name" required>
            </div>
            <div class="form-group">
                <label for="recipient-name" class="col-form-label">Description</label>
                <input type="text" class="form-control" id="recipient-name" name="description" required>
            </div>
            <div class="form-group">
                <label for="inputState">User Status</label>
                <select id="inputState" class="form-control" name="status" required>
                    <option selected>Choose...</option>
                    <option value="1">Active</option>
                    <option value="0">De-active</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
            <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
        </form>
</div>