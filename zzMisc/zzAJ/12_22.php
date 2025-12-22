<?php 
require_once __DIR__ . '/../../src/common/autoload.php';
require_once __DIR__ . '/../../src/common/constants.php';

// TODO: import includes, then create a form for employees to fill out

/***
 * 
 * Test out different data structures, different data types
 * 
 * Can we handle creating forms to take in prayer requests, 
 * contact list, etc.
 * 
 */

/**
 * Generic Data Model Class
 * Demonstrates getters and setters for different data types
 * Can be extended for prayer requests, contact lists, etc.
 * 
 * @Author: AJ Javadi
 * @Email: amirjavadi25@gmail.com
 * @Date: 2024-12-22
 * @Description: Test class for getters/setters with various data types
 */
class GenericDataModel
{
    // ============================================
    // PRIVATE PROPERTIES
    // ============================================
    
    /**
     * @var mysqli|null Database connection
     */
    private ?mysqli $_mysqli = null;
    
    /**
     * @var int|null ID of the record
     */
    private ?int $_id = null;
    
    /**
     * @var string Name field
     */
    private string $_name = '';
    
    /**
     * @var string Email field
     */
    private string $_email = '';
    
    /**
     * @var string Phone number field
     */
    private string $_phone = '';
    
    /**
     * @var string Description/notes field
     */
    private string $_description = '';
    
    /**
     * @var array Tags/categories array
     */
    private array $_tags = [];
    
    /**
     * @var bool Active status flag
     */
    private bool $_isActive = true;
    
    /**
     * @var float Numeric value (for distance, amount, etc.)
     */
    private float $_numericValue = 0.0;
    
    /**
     * @var DateTime|null Date/time field
     */
    private ?DateTime $_dateCreated = null;
    
    /**
     * @var array Additional metadata
     */
    private array $_metadata = [];
    
    // ============================================
    // CONSTRUCTOR
    // ============================================
    
    /**
     * Constructor
     * @param mysqli|null $mysqli Database connection
     */
    public function __construct(?mysqli $mysqli = null)
    {
        $this->_mysqli = $mysqli;
        $this->_dateCreated = new DateTime();
    }
    
    // ============================================
    // GETTERS
    // ============================================
    
    /**
     * Get database connection
     * @return mysqli|null
     */
    public function getMysqli(): ?mysqli
    {
        return $this->_mysqli;
    }
    
    /**
     * Get ID
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->_id;
    }
    
    /**
     * Get name
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }
    
    /**
     * Get email
     * @return string
     */
    public function getEmail(): string
    {
        return $this->_email;
    }
    
    /**
     * Get phone
     * @return string
     */
    public function getPhone(): string
    {
        return $this->_phone;
    }
    
    /**
     * Get description
     * @return string
     */
    public function getDescription(): string
    {
        return $this->_description;
    }
    
    /**
     * Get tags
     * @return array
     */
    public function getTags(): array
    {
        return $this->_tags;
    }
    
    /**
     * Get active status
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->_isActive;
    }
    
    /**
     * Get numeric value
     * @return float
     */
    public function getNumericValue(): float
    {
        return $this->_numericValue;
    }
    
    /**
     * Get date created
     * @return DateTime|null
     */
    public function getDateCreated(): ?DateTime
    {
        return $this->_dateCreated;
    }
    
    /**
     * Get metadata
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->_metadata;
    }
    
    /**
     * Get specific metadata value by key
     * @param string $key Metadata key
     * @return mixed|null
     */
    public function getMetadataValue(string $key)
    {
        return $this->_metadata[$key] ?? null;
    }
    
    // ============================================
    // SETTERS
    // ============================================
    
    /**
     * Set database connection
     * @param mysqli|null $mysqli Database connection
     * @return self
     */
    public function setMysqli(?mysqli $mysqli): self
    {
        $this->_mysqli = $mysqli;
        return $this;
    }
    
    /**
     * Set ID
     * @param int|null $id Record ID
     * @return self
     */
    public function setId(?int $id): self
    {
        $this->_id = $id;
        return $this;
    }
    
    /**
     * Set name
     * @param string $name Name value
     * @return self
     */
    public function setName(string $name): self
    {
        $this->_name = trim($name);
        return $this;
    }
    
    /**
     * Set email with validation
     * @param string $email Email address
     * @return self
     * @throws InvalidArgumentException If email is invalid
     */
    public function setEmail(string $email): self
    {
        $email = trim($email);
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email address: ' . $email);
        }
        $this->_email = $email;
        return $this;
    }
    
    /**
     * Set phone number
     * @param string $phone Phone number
     * @return self
     */
    public function setPhone(string $phone): self
    {
        // Remove non-numeric characters for storage
        $this->_phone = preg_replace('/[^0-9+\-() ]/', '', trim($phone));
        return $this;
    }
    
    /**
     * Set description
     * @param string $description Description text
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->_description = trim($description);
        return $this;
    }
    
    /**
     * Set tags array
     * @param array $tags Array of tags
     * @return self
     */
    public function setTags(array $tags): self
    {
        $this->_tags = array_filter(array_map('trim', $tags));
        return $this;
    }
    
    /**
     * Add a single tag
     * @param string $tag Tag to add
     * @return self
     */
    public function addTag(string $tag): self
    {
        $tag = trim($tag);
        if (!empty($tag) && !in_array($tag, $this->_tags, true)) {
            $this->_tags[] = $tag;
        }
        return $this;
    }
    
    /**
     * Remove a tag
     * @param string $tag Tag to remove
     * @return self
     */
    public function removeTag(string $tag): self
    {
        $key = array_search($tag, $this->_tags, true);
        if ($key !== false) {
            unset($this->_tags[$key]);
            $this->_tags = array_values($this->_tags); // Re-index array
        }
        return $this;
    }
    
    /**
     * Set active status
     * @param bool $isActive Active status
     * @return self
     */
    public function setIsActive(bool $isActive): self
    {
        $this->_isActive = $isActive;
        return $this;
    }
    
    /**
     * Set numeric value with validation
     * @param float $value Numeric value
     * @return self
     * @throws InvalidArgumentException If value is negative
     */
    public function setNumericValue(float $value): self
    {
        if ($value < 0) {
            throw new InvalidArgumentException('Numeric value cannot be negative');
        }
        $this->_numericValue = $value;
        return $this;
    }
    
    /**
     * Set date created
     * @param DateTime|string|null $date Date object or string (YYYY-MM-DD format)
     * @return self
     * @throws InvalidArgumentException If date format is invalid
     */
    public function setDateCreated($date): self
    {
        if ($date === null) {
            $this->_dateCreated = null;
            return $this;
        }
        
        if (is_string($date)) {
            try {
                $this->_dateCreated = new DateTime($date);
            } catch (Exception $e) {
                throw new InvalidArgumentException('Invalid date format: ' . $date);
            }
        } elseif ($date instanceof DateTime) {
            $this->_dateCreated = $date;
        } else {
            throw new InvalidArgumentException('Date must be DateTime object or string');
        }
        
        return $this;
    }
    
    /**
     * Set metadata array
     * @param array $metadata Metadata array
     * @return self
     */
    public function setMetadata(array $metadata): self
    {
        $this->_metadata = $metadata;
        return $this;
    }
    
    /**
     * Set specific metadata value
     * @param string $key Metadata key
     * @param mixed $value Metadata value
     * @return self
     */
    public function setMetadataValue(string $key, $value): self
    {
        $this->_metadata[$key] = $value;
        return $this;
    }
    
    /**
     * Remove metadata value by key
     * @param string $key Metadata key to remove
     * @return self
     */
    public function removeMetadataValue(string $key): self
    {
        unset($this->_metadata[$key]);
        return $this;
    }
    
    // ============================================
    // UTILITY METHODS
    // ============================================
    
    /**
     * Convert object to array
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->_id,
            'name' => $this->_name,
            'email' => $this->_email,
            'phone' => $this->_phone,
            'description' => $this->_description,
            'tags' => $this->_tags,
            'isActive' => $this->_isActive,
            'numericValue' => $this->_numericValue,
            'dateCreated' => $this->_dateCreated ? $this->_dateCreated->format('Y-m-d H:i:s') : null,
            'metadata' => $this->_metadata
        ];
    }
    
    /**
     * Populate object from array
     * @param array $data Data array
     * @return self
     */
    public function fromArray(array $data): self
    {
        if (isset($data['id'])) {
            $this->setId((int)$data['id']);
        }
        if (isset($data['name'])) {
            $this->setName($data['name']);
        }
        if (isset($data['email'])) {
            $this->setEmail($data['email']);
        }
        if (isset($data['phone'])) {
            $this->setPhone($data['phone']);
        }
        if (isset($data['description'])) {
            $this->setDescription($data['description']);
        }
        if (isset($data['tags'])) {
            $this->setTags($data['tags']);
        }
        if (isset($data['isActive'])) {
            $this->setIsActive((bool)$data['isActive']);
        }
        if (isset($data['numericValue'])) {
            $this->setNumericValue((float)$data['numericValue']);
        }
        if (isset($data['dateCreated'])) {
            $this->setDateCreated($data['dateCreated']);
        }
        if (isset($data['metadata'])) {
            $this->setMetadata($data['metadata']);
        }
        
        return $this;
    }
}

// ============================================
// TEST CODE
// ============================================

$mysqli = new MySQLi(HOST, USER, PASSWORD, DATABASE, PORT);

if ($mysqli->connect_error) {
    echo 'Connection failed! \n 
    Error #' . $mysqli->connect_errno . ': ' . $mysqli->connect_error;
    exit(0);
}

// Test the getters and setters
$model = new GenericDataModel($mysqli);

// Test setters with method chaining
$model->setName('John Doe')
      ->setEmail('john.doe@example.com')
      ->setPhone('555-123-4567')
      ->setDescription('Test contact entry')
      ->addTag('friend')
      ->addTag('work')
      ->setNumericValue(42.5)
      ->setIsActive(true)
      ->setMetadataValue('source', 'manual')
      ->setMetadataValue('priority', 'high');

// Test getters
echo "ID: " . ($model->getId() ?? 'null') . "\n";
echo "Name: " . $model->getName() . "\n";
echo "Email: " . $model->getEmail() . "\n";
echo "Phone: " . $model->getPhone() . "\n";
echo "Description: " . $model->getDescription() . "\n";
echo "Tags: " . implode(', ', $model->getTags()) . "\n";
echo "Is Active: " . ($model->getIsActive() ? 'Yes' : 'No') . "\n";
echo "Numeric Value: " . $model->getNumericValue() . "\n";
echo "Date Created: " . $model->getDateCreated()->format('Y-m-d H:i:s') . "\n";
echo "Metadata Source: " . $model->getMetadataValue('source') . "\n";

// Test toArray
echo "\n--- Array Representation ---\n";
print_r($model->toArray());

$mysqli->close();

echo "\nConnection successful!";

