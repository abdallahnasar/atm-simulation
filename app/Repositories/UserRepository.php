<?php
namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }
    public function all()
    {
        return $this->model->orderBy('created_at', 'desc')->paginate(10);
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        $data['pin'] = Hash::make($data['pin']);
        return $this->model->create($data);
    }

    public function update(User $user, array $data)
    {
        if (isset($data['pin'])) {
            $data['pin'] = Hash::make($data['pin']);
        }else{
            unset($data['pin']);
        }
        return $user->update($data);
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }
}
