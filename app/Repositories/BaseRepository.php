<?php

namespace App\Repositories;

class BaseRepository
{
    /**
     * DB Model
     */
    protected $model;

    /**
     * Create new db data
     * @param array $param
     * @return Illuminate\Support\Collection
     */
    public function create(array $param)
    {
        return $this->model->create($param);
    }

    /**
     * Update db data
     * @param int $id
     * @param array $params
     * @return bool
     */
    public function update(int $id, array $params)
    {
        if ($this->isExisting($id)) {
            return $this->model->find($id)->update($params);
        }

        return false;
    }

    /**
     * Soft delete the data
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        return $this->model->find($id)->delete();
    }

    /**
     * Check data if existing
     * @param int $id
     * @return bool
     */
    public function isExisting(int $id)
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Retrieve data based on the id
     * @param int $id
     * @return Illuminate\Support\Collection
     */
    public function find(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * Retrieve data based on the parameters
     * @param array $params
     * @return Illuminate\Support\Collection
     */
    public function getWhere(array $params)
    {
        return $this->model->where($params)->get();
    }

    /**
     * Multiple creation of data
     * @param array $params
     */
    public function insert(array $params)
    {
        return $this->model->insert($params);
    }
}