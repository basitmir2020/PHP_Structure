<?php
namespace App\Model;

class AccountModel {
    public ?int $id;
    public string $name;
    public string $email;

    /**
     * AccountModel constructor.
     *
     * @param string $name
     * @param string $email
     * @param int|null $id
     */
    public function __construct(string $name, string $email, ?int $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Converts the model to an associative array.
     * Useful for API responses or other array-based operations.
     *
     * @return array
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
?>
