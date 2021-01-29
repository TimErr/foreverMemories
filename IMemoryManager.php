<?php
interface IMemoryManager {
    public function create($place, $date, $description, $picture, $userId);
    public function read($id);
    public function readAll();
    public function update($id, $place, $date, $description, $picture);
    public function delete($id);
}
?>
