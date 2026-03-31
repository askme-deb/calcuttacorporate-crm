<div class="lock-screen">
    <h2>Screen Locked</h2>
    <p>Please enter your password to continue.</p>

    @if ($errorMessage)
        <div class="alert alert-danger">{{ $errorMessage }}</div>
    @endif

    <form wire:submit.prevent="unlock">
        <div>
            <input type="password" wire:model.defer="password" placeholder="Enter password" required />
        </div>
        <button type="submit">Unlock</button>
    </form>
    <style>
        .lock-screen {
            text-align: center;
            padding: 20px;
            max-width: 400px;
            margin: 100px auto;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: #f9f9f9;
        }
    </style>



</div>

