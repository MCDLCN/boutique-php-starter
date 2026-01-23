<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use RuntimeException;

class UserController extends Controller
{
    private ?UserRepository $userRepository = null;

    public function __construct(UserRepository $userRepository = null)
    {
        $this->userRepository = $userRepository;
    }

    private function getUserRepository(): UserRepository
    {
        if (!$this->userRepository instanceof UserRepository) {
            // Lazy load the repository if not injected
            $pdo = \App\Database::getInstance();
            $addressRepo = new \App\Repository\AddressRepository($pdo);
            $this->userRepository = new UserRepository($pdo, $addressRepo);
        }
        return $this->userRepository;
    }

    /**
     * Register a new user (internal method)
     * @throws RuntimeException if email already exists or password is empty
     */
    private function register(string $name, string $email, string $password): User
    {
        // Check if email already exists
        if ($this->getUserRepository()->existsEmail($email)) {
            throw new RuntimeException('Email already registered');
        }

        // Create new user
        $registrationDate = date('Y-m-d H:i:s');
        $user = new User(
            id: null,
            name: $name,
            email: $email,
            registrationDate: $registrationDate
        );

        // Save user with password hash
        $this->getUserRepository()->save($user, $password);

        return $user;
    }

    /**
     * Login user with email and password (internal method)
     * @throws RuntimeException if credentials are invalid
     */
    private function login(string $email, string $password): User
    {
        $user = $this->getUserRepository()->verifyCredentials($email, $password);

        if (!$user instanceof User) {
            throw new RuntimeException('Invalid credentials');
        }

        // Set session or return authenticated user
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_email'] = $user->getEmail();
        $_SESSION['user_name'] = $user->getName();

        return $user;
    }

    /**
     * Logout user (internal method)
     */
    private function logout(): void
    {
        session_destroy();
    }

    /**
     * Handle registration from form submission
     * Returns JSON response
     */
    public function registerAction(): void
    {
        header('Content-Type: application/json');

        try {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($name) || empty($email) || empty($password)) {
                echo json_encode(['success' => false, 'message' => 'All fields are required']);
                exit;
            }

            $user = $this->register($name, $email, $password);
            
            // Automatically login the user after registration
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_email'] = $user->getEmail();
            $_SESSION['user_name'] = $user->getName();
            
            echo json_encode(['success' => true, 'message' => 'Registration successful']);
            exit;
        } catch (RuntimeException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }

    /**
     * Handle login from form submission
     * Returns JSON response
     */
    public function loginAction(): void
    {
        header('Content-Type: application/json');

        try {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                echo json_encode(['success' => false, 'message' => 'Email and password are required']);
                exit;
            }

            $this->login($email, $password);
            echo json_encode(['success' => true, 'message' => 'Login successful']);
            exit;
        } catch (RuntimeException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }

    /**
     * Handle logout
     */
    public function logoutAction(): void
    {
        $this->logout();
        $referrer = $_SERVER['HTTP_REFERER'] ?? '/';
        header('Location: ' . $referrer);
        exit;
    }

    /**
     * Get currently logged in user
     */
    public function getCurrentUser(): ?User
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        return $this->getUserRepository()->find($_SESSION['user_id']);
    }

    /**
     * Check if user is logged in
     */
    public function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Get user by ID
     */
    public function getUser(int $id): ?User
    {
        return $this->getUserRepository()->find($id);
    }

    /**
     * Get all users
     */
    public function getAllUsers(): array
    {
        return $this->getUserRepository()->findAll();
    }

    /**
     * Update user profile
     */
    public function updateProfile(User $user): void
    {
        $this->getUserRepository()->update($user);
    }

    /**
     * Change user password
     */
    public function changePassword(User $user, string $newPassword): void
    {
        $this->getUserRepository()->updatePassword($user, $newPassword);
    }

    /**
     * Delete user account
     */
    public function deleteUser(User $user): void
    {
        $this->getUserRepository()->delete($user);
    }
}
