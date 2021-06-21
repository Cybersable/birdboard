<div class="card">
    <div class="card-header">
        Inviting a user
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('projects.invitations.store', $project) }}">
            @csrf
            <div class="form-group">
                <label for="email" class="form-label">User email:</label>
                <input type="email" id="email" name="email" class="form-control">
            </div>
            @include('errors', ['bag' => 'invitations'])
            <button class="btn btn-primary">
                Invite
            </button>
        </form>
    </div>
</div>
