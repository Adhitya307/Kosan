<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Booking Kosan' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #e0e4fc;
            --secondary: #3f37c9;
            --accent: #4895ef;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --light-gray: #f1f3f8;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --white: #ffffff;
            --shadow-sm: 0 2px 10px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 15px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 8px 20px rgba(0, 0, 0, 0.15);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        body {
            background-color: #f5f7ff;
            font-family: 'Poppins', sans-serif;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(67, 97, 238, 0.05) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(72, 149, 239, 0.05) 0%, transparent 20%);
            min-height: 100vh;
        }

        .booking-card {
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            border: none;
            overflow: hidden;
            transition: var(--transition);
        }

        .booking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary), var(--accent)) !important;
            border-bottom: none;
            padding: 1.25rem;
        }

        .btn-booking {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            padding: 0.75rem;
            transition: var(--transition);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.2);
        }

        .btn-booking:hover {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(67, 97, 238, 0.3);
        }

        .btn-booking:active {
            transform: translateY(0);

        }

        .kamar-foto-gallery {
            position: relative;
            margin: 1.5rem 0;
        }

        .carousel {
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .carousel-item img {
            height: 300px;
            object-fit: cover;
            width: 100%;
        }

        .carousel-control-prev, .carousel-control-next {
            width: 40px;
            height: 40px;
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.8;
            transition: var(--transition);
        }

        .carousel-control-prev:hover, .carousel-control-next:hover {
            opacity: 1;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .carousel-indicators button {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin: 0 5px;
            background-color: rgba(255, 255, 255, 0.5);
            border: none;
        }

        .carousel-indicators button.active {
            background-color: var(--white);
        }

        .fasilitas-list {
            list-style: none;
            padding-left: 0;
        }

        .fasilitas-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
        }

        .fasilitas-list li:before {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: var(--primary);
            margin-right: 0.75rem;
            font-size: 0.9rem;
        }

        .detail-item {
            display: flex;
            margin-bottom: 0.75rem;
            align-items: center;
        }

        .detail-item strong {
            min-width: 80px;
            display: inline-block;
            color: var(--gray);
        }

        .form-control {
            border-radius: var(--radius-sm);
            padding: 0.75rem;
            border: 1px solid rgba(0, 0, 0, 0.1);
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
        }

        .alert {
            border-radius: var(--radius-sm);
        }

        @media (max-width: 768px) {
            .carousel-item img {
                height: 250px;
            }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>