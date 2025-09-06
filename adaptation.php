<?php
// adoptation.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Pet Adoption</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f9f9f9;
      color: #222;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #fff;
      padding: 16px 28px;
      font-weight: 600;
      border-bottom: 1px solid #eee;
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    .logo { font-size: 22px; font-weight: 700; }

    nav a {
      margin-left: 18px;
      text-decoration: none;
      color: #000;
      font-weight: 600;
      padding: 8px 14px;
      border-radius: 8px;
      background: #ff6600;
      color: #fff;
      transition: background .2s ease;
    }
    nav a:hover { background: #e55b00; }

    /* Hero section */
    .hero {
      text-align: center;
      padding: 60px 20px;
      background: linear-gradient(to right, #ffecd2, #fcb69f);
    }
    .hero h1 {
      font-size: 36px;
      margin-bottom: 12px;
    }
    .hero p {
      font-size: 18px;
      color: #333;
      max-width: 600px;
      margin: 0 auto;
    }

    /* adoption cards */
    .cards {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      padding: 36px;
      justify-content: center;
    }
    .card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.08);
      width: 300px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
    }
    .card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }
    .card-content {
      padding: 16px;
    }
    .card-content h3 {
      margin: 0 0 8px;
    }
    .card-content p {
      margin: 4px 0;
      font-size: 14px;
      color: #444;
    }
    .btn {
      background: #ff6600;
      color: #fff;
      border: none;
      padding: 10px 14px;
      border-radius: 8px;
      font-weight: 600;
      text-decoration: none;
      display: inline-block;
      margin-top: 12px;
      text-align: center;
    }
    .btn:hover { background: #e55b00; }

    /* form */
    .form-section {
      padding: 40px 20px;
      max-width: 500px;
      margin: auto;
    }
    .form-section h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    form {
      display: flex;
      flex-direction: column;
      gap: 14px;
    }
    input, select {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
    }
    input:focus, select:focus {
      outline: none;
      border-color: #ff6600;
    }
    form .btn {
      align-self: flex-start;
    }

    /* responsive */
    @media (max-width: 768px) {
      .cards {
        padding: 20px;
      }
      .card {
        width: 100%;
      }
    }
  </style>
</head>
<body>

<header>
  <div class="logo">🐾 AdoptPet</div>
  <nav>
    <a href="#upforadoption">Up for Adoption</a>
    <a href="#giveadoption">Give for Adoption</a>
  </nav>
</header>

<main>
  <!-- homepage hero -->
  <section class="hero">
    <h1>Find Your New Best Friend 🐶🐱</h1>
    <p>Every pet deserves a loving home. Adopt today and bring happiness to both your life and theirs.</p>
  </section>

  <!-- Up for Adoption -->
  <section id="upforadoption">
    <h2 style="text-align:center; margin-top:30px;">Pets Available for Adoption</h2>
    <div class="cards">
      <div class="card">
        <img src="https://placekitten.com/400/250" alt="Pet 1">
        <div class="card-content">
          <h3>Fluffy</h3>
          <p><strong>ID:</strong> ADP001</p>
          <p><strong>Date:</strong> 5 Sept 2025</p>
          <p>2-year-old playful cat looking for a cozy home.</p>
          <a class="btn" href="#">Adopt Now</a>
        </div>
      </div>

      <div class="card">
        <img src="https://placedog.net/500/280?id=1" alt="Pet 2">
        <div class="card-content">
          <h3>Buddy</h3>
          <p><strong>ID:</strong> ADP002</p>
          <p><strong>Date:</strong> 2 Sept 2025</p>
          <p>Friendly golden retriever, 3 years old, loves kids.</p>
          <a class="btn" href="#">Adopt Now</a>
        </div>
      </div>

      <div class="card">
        <img src="https://placekitten.com/401/251" alt="Pet 3">
        <div class="card-content">
          <h3>Mittens</h3>
          <p><strong>ID:</strong> ADP003</p>
          <p><strong>Date:</strong> 1 Sept 2025</p>
          <p>Sweet kitten, 6 months, loves cuddles.</p>
          <a class="btn" href="#">Adopt Now</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Give for Adoption -->
  <section id="giveadoption" class="form-section">
    <h2>Give a Pet for Adoption</h2>
    <form>
      <input type="text" placeholder="Pet Name" required />
      <input type="number" placeholder="Age (in years)" required />
      <input type="text" placeholder="Breed" required />
      <select required>
        <option value="">Select Animal Type</option>
        <option>Dog</option>
        <option>Cat</option>
        <option>Bird</option>
        <option>Other</option>
      </select>
      <button class="btn" type="submit">Submit</button>
    </form>
  </section>
</main>

</body>
</html>
