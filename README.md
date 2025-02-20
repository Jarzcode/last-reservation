# Last Reservation

## Overview

Last Reservation is an API designed to manage reservations for multiple restaurants. It is built with scalability in mind, following DDD and Hexagonal Architecture principles, leveraging event-driven architecture and asynchronous processing to handle high loads efficiently.

## Features

- **Multi-Restaurant Support**: Manage reservations for multiple restaurants within a single system.
- **Event-Driven Architecture**: Utilizes events to decouple components and improve scalability.
- **Asynchronous Processing**: Designed to handle operations asynchronously, ensuring the system remains responsive under heavy load.
- **Scalable**: Built to scale horizontally, making it suitable for large-scale deployments.

## Technologies

- **PHP**: The core language used for development.
- **Symfony**: The primary framework used to build the API.
- **Doctrine**: ORM for database interactions.
- **RabbitMQ**: Message broker for handling asynchronous tasks.
- **MySQL**: Database for storing reservation data.

## How email notifications
A cronjob is set up to run every minute to check for reservations that will start in the next hour.
If any reservations are found, an email notification is sent to the customer.

## Getting Started

### Prerequisites

- PHP 8.0 or higher
- Composer
- Docker and Docker Compose

### Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/yourusername/last-reservation.git
    cd last-reservation
    ```

2. Install dependencies:
    ```sh
    composer install
    ```

3. Set up the environment variables:
    ```sh
    cp .env.example .env
    ```

4. Start the services using Docker Compose:
    ```sh
    docker-compose up -d
    ```

5. Run database migrations:
    ```sh
    php bin/console doctrine:migrations:migrate
    ```

## Usage

### API Endpoints

- **List Reservations**: `GET /reservations`
- **Update Reservation**: `PUT /reservations/{id}`
- **Cancel a Reservation**: `PATCH /reservations/{id}/cancel`
- **List Available Times**: `GET /reservations/availability`

### Example Request

To list all reservations for a restaurant:
```sh
curl -X GET "http://localhost:8000/reservations?restaurantId=your-restaurant-id"
```

To update a reservation:
```sh
curl -X PUT "http://localhost:8000/reservations/{id}" \
    -H "Content-Type: application/json" \
    -d '{
        "restaurantId": "your-restaurant-id",
        "party_size": 4,
        "name": "John Doe",
        "start": "2023-10-10T18:00:00",
        "end": "2023-10-10T20:00:00"
    }'
```

## Contributing

Contributions are welcome! Please open an issue or submit a pull request.

## License

This project is licensed under the MIT License.
```
