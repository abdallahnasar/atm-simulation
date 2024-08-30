

    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $user->name ?? old('name') }}" required>
        @error('name')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group">
        <label for="debit_card_number">Debit Card Number</label>
        <input type="text" name="debit_card_number" class="form-control @error('debit_card_number') is-invalid @enderror" value="{{ $user->debit_card_number ?? old('debit_card_number') }}" required>
        @error('debit_card_number')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group">
        <label for="pin">PIN</label>
        <input type="text" name="pin" class="form-control @error('pin') is-invalid @enderror" value="" {{isset($user)?'':'required'}}>
        @error('pin')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group">
        <label for="balance">Balance</label>
        <input type="text" name="balance" class="form-control @error('balance') is-invalid @enderror" value="{{ $user->balance ?? old('balance') }}" required>
        @error('balance')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success">{{ isset($user) ? 'Update User' : 'Create User' }}</button>
    </div>
