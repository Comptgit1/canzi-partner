<?php
$success = false;
$errors  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require_once __DIR__ . '/config/db.php';

  $business_name    = trim($_POST['business_name']    ?? '');
  $business_type    = trim($_POST['business_type']    ?? '');
  $city             = trim($_POST['city']             ?? '');
  $business_address = trim($_POST['business_address'] ?? '');
  $business_phone   = trim($_POST['business_phone']   ?? '');
  $owner_name       = trim($_POST['owner_name']       ?? '');
  $phone_number     = trim($_POST['phone_number']     ?? '');
  $user_notes       = trim($_POST['user_notes']       ?? '');
  $privacy          = isset($_POST['privacy']);

  if (!$business_name) $errors[] = 'Business name is required.';
  if (!$business_type) $errors[] = 'Business type is required.';
  if (!$city)          $errors[] = 'City is required.';
  if (!$owner_name)    $errors[] = 'Owner name is required.';
  if (!$phone_number)  $errors[] = 'Phone number is required.';
  if (!$privacy)       $errors[] = 'You must accept the Privacy Policy.';
  if (empty($errors)) {
    $stmt = $pdo->prepare("
            INSERT INTO store_applications
                (status,city,business_name,business_phone,business_address,
                 business_type,owner_name,phone_number,user_notes,created_at,updated_at)
            VALUES ('pending',:city,:business_name,:business_phone,:business_address,
                :business_type,:owner_name,:phone_number,:user_notes,NOW(),NOW())
        ");
    $stmt->execute([
      ':city' => $city,
      ':business_name' => $business_name,
      ':business_phone' => $business_phone,
      ':business_address' => $business_address,
      ':business_type' => $business_type,
      ':owner_name' => $owner_name,
      ':phone_number' => $phone_number,
      ':user_notes' => $user_notes,
    ]);
    $success = true;
  }
}
$cities = ['Laayoun', 'Dakhla', 'Other'];
$types  = ['Restaurant', 'Cafe / Snack', 'Supermarket', 'Pharmacy', 'Shop', 'Other'];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Become a Partner – Canzi Tech</title>
  <link rel="icon" href="images/canzi-icon.png" type="images/png" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    :root {
      --red: #f51e1e;
      --red2: #d01515;
      --orange: #ff6b00;
      --white: #fff;
      --black: #1a1a1a;
      --gray1: #333;
      --gray2: #555;
      --gray3: #888;
      --gray4: #bbb;
      --gray5: #e5e5e5;
      --gray6: #f5f5f5;
      --radius: 12px;
      --radius-lg: 16px;
    }

    html {
      scroll-behavior: smooth
    }

    body {
      font-family: 'Inter', sans-serif;
      color: var(--black);
      background: var(--white);
      font-size: 16px;
      line-height: 1.6
    }

    /* ── NAVBAR ── */
    .navbar {
      position: sticky;
      top: 0;
      z-index: 200;
      height: 70px;
      display: flex;
      align-items: center;
      padding: 0 40px;
      background: var(--white);
      border-bottom: 1px solid var(--gray5);
    }

    .navbar-logo {
      display: flex;
      align-items: center;
      text-decoration: none;
    }

    .navbar-logo img {
      height: 42px;
      width: auto;
    }

    @media(max-width:768px) {
      .navbar {
        padding: 0 20px;
      }
    }

    /* ── HERO ── */
    .hero {
      position: relative;
      display: grid;
      grid-template-columns: 1fr 1fr;
      min-height: calc(100vh - 70px);
      overflow: hidden;
    }

    @media(max-width:960px) {
      .hero {
        grid-template-columns: 1fr;
      }
    }

    /* LEFT — form panel */
    .hero-form-panel {
      position: relative;
      z-index: 2;
      background: #faf9f7;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 60px 80px 60px 64px;

      clip-path: polygon(0 0, 100% 0, calc(100% - 80px) 100%, 0 100%);
    }

    @media(max-width:960px) {
      .hero-form-panel {
        clip-path: none;
        padding: 48px 24px;
      }
    }

    .hero-form-panel .form-card {
      width: 100%;
      max-width: 560px;
      position: relative;
      z-index: 1;
    }

    .hero-form-panel::before {
      content: '';
      position: absolute;
      width: 400px;
      height: 400px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(245, 30, 30, .08) 0%, transparent 70%);
      top: -60px;
      left: -80px;
      pointer-events: none;
    }

    .hero-form-panel::after {
      content: '';
      position: absolute;
      width: 300px;
      height: 300px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(245, 30, 30, .06) 0%, transparent 70%);
      bottom: -40px;
      right: 40px;
      pointer-events: none;
    }

    /* RIGHT — image panel */
    .hero-img-panel {
      position: relative;
      overflow: hidden;
      min-height: 520px;
      margin-left: -60px;
    }

    .hero-img-panel img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
      display: block;
    }

    .hero-img-panel .hero-img-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(0, 0, 0, .5) 0%, rgba(0, 0, 0, .2) 50%, rgba(0, 0, 0, .1) 100%);
    }

    .hero-img-text {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 2;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: 52px 52px 64px 80px;
    }

    .hero-title {
      font-size: clamp(1.8rem, 3vw, 2.8rem);
      font-weight: 800;
      color: var(--white);
      line-height: 1.2;
      margin-bottom: 14px;
      text-shadow: 0 2px 20px rgba(0, 0, 0, .4);
    }

    .hero-title span {
      color: #ffd54f;
    }

    .hero-subtitle {
      font-size: .95rem;
      color: rgba(255, 255, 255, .85);
      max-width: 380px;
      line-height: 1.7;
      text-shadow: 0 1px 8px rgba(0, 0, 0, .3);
    }

    @media(max-width:960px) {
      .hero-img-panel {
        min-height: 320px;
        margin-left: 0;
      }

      .hero-img-text {
        padding: 32px 24px 40px;
      }
    }

    /* ── FORM CARD ── */
    .form-card {
      background: var(--white);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 4px 32px rgba(0, 0, 0, .10);
    }

    .form-card-title {
      font-size: 1.35rem;
      font-weight: 800;
      color: var(--black);
      margin-bottom: 6px;
    }

    .form-card-sub {
      font-size: .85rem;
      color: var(--gray3);
      margin-bottom: 28px;
    }

    /* ── FIELDS ── */
    .field {
      margin-bottom: 16px;
    }

    .field label {
      display: block;
      font-size: .8rem;
      font-weight: 600;
      color: var(--gray1);
      margin-bottom: 6px;
    }

    .field label .req {
      color: var(--red);
      margin-left: 2px;
    }

    .field input[type=text],
    .field input[type=tel],
    .field input[type=email],
    .field select,
    .field textarea {
      width: 100%;
      padding: 13px 16px;
      border: 1.5px solid var(--gray5);
      border-radius: 12px;
      font-family: 'Inter', sans-serif;
      font-size: .925rem;
      color: var(--black);
      background: var(--white);
      outline: none;
      transition: border-color .15s, box-shadow .15s;
      appearance: none;
      -webkit-appearance: none;
    }

    .field input:focus,
    .field select:focus,
    .field textarea:focus {
      border-color: var(--red);
      box-shadow: 0 0 0 3px rgba(245, 30, 30, .1);
    }

    .field select {
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23888' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 12px center;
      padding-right: 36px;
      cursor: pointer;
    }

    .field textarea {
      resize: vertical;
      min-height: 80px;
    }

    .field-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    @media(max-width:500px) {
      .field-row {
        grid-template-columns: 1fr;
      }
    }

    /* ── TYPE PILLS ── */
    .type-pills {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 8px;
      margin-top: 4px;
    }

    .type-pill {
      position: relative;
    }

    .type-pill input {
      position: absolute;
      opacity: 0;
      width: 0;
      height: 0;
    }

    .type-pill-label {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      padding: 9px 6px;
      border: 1.5px solid var(--gray5);
      border-radius: 14px;
      font-size: .75rem;
      font-weight: 600;
      color: var(--gray3);
      background: var(--gray6);
      cursor: pointer;
      transition: all .15s;
      white-space: nowrap;
    }

    .type-pill-label svg {
      width: 15px;
      height: 15px;
      flex-shrink: 0;
      stroke: var(--gray3);
      fill: none;
      stroke-width: 1.8;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    .type-pill input:checked+.type-pill-label {
      border-color: var(--red);
      background: #fff0f0;
      color: var(--red);
    }

    .type-pill input:checked+.type-pill-label svg {
      stroke: var(--red);
    }

    @media(max-width:420px) {
      .type-pills {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    /* ── CHECKBOXES ── */
    .chk-group {
      display: flex;
      flex-direction: column;
      gap: 14px;
      margin-bottom: 22px;
    }

    .chk-item {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      cursor: pointer;
      position: relative;
    }

    .chk-item input[type=checkbox] {
      appearance: none;
      -webkit-appearance: none;
      width: 20px;
      height: 20px;
      min-width: 20px;
      border: 2px solid var(--gray4);
      border-radius: 6px;
      background: var(--white);
      cursor: pointer;
      margin-top: 1px;
      transition: all .15s;
      position: relative;
    }

    .chk-item input[type=checkbox]::after {
      content: '';
      position: absolute;
      left: 5px;
      top: 2px;
      width: 6px;
      height: 10px;
      border: 2px solid var(--white);
      border-top: none;
      border-left: none;
      transform: rotate(45deg) scale(0);
      transition: transform .15s;
    }

    .chk-item.wa-chk input[type=checkbox]:checked {
      background: #22c55e;
      border-color: #22c55e;
    }

    .chk-item.prv-chk input[type=checkbox]:checked {
      background: var(--red);
      border-color: var(--red);
    }

    .chk-item input[type=checkbox]:checked::after {
      transform: rotate(45deg) scale(1);
    }

    .chk-label {
      font-size: .85rem;
      color: var(--gray1);
      line-height: 1.5;
    }

    .chk-label a {
      color: var(--red);
      font-weight: 600;
      text-decoration: underline;
    }

    .wa-badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      color: #16a34a;
      font-weight: 700;
    }

    .wa-badge svg {
      width: 16px;
      height: 16px;
      fill: #16a34a;
      flex-shrink: 0;
    }

    .field-error {
      font-size: .75rem;
      color: var(--red);
      margin-top: 4px;
      font-weight: 500;
    }

    /* ── ERRORS BOX ── */
    .errors-box {
      background: #fef2f2;
      border: 1px solid #fecaca;
      border-radius: 12px;
      padding: 12px 16px;
      margin-bottom: 20px;
    }

    .errors-box p {
      font-size: .82rem;
      color: #dc2626;
      display: flex;
      align-items: center;
      gap: 6px;
      padding: 2px 0;
    }

    .errors-box p::before {
      content: '';
      display: inline-block;
      width: 6px;
      height: 6px;
      background: #dc2626;
      border-radius: 50%;
      flex-shrink: 0;
    }

    /* ── SUBMIT ── */
    .btn-submit {
      width: 100%;
      padding: 15px;
      background: var(--red);
      color: var(--white);
      border: none;
      border-radius: 12px;
      font-family: 'Inter', sans-serif;
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: background .2s, transform .1s;
    }

    .btn-submit:hover {
      background: var(--red2);
    }

    .btn-submit:active {
      transform: scale(.99);
    }

    .btn-submit svg {
      width: 18px;
      height: 18px;
      stroke: var(--white);
      fill: none;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    .btn-note {
      font-size: .75rem;
      color: var(--gray4);
      text-align: center;
      margin-top: 10px;
    }

    /* ── SUCCESS ── */
    .success-card {
      text-align: center;
      padding: 20px 0;
    }

    .success-icon {
      width: 72px;
      height: 72px;
      border-radius: 50%;
      background: #dcfce7;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 16px;
    }

    .success-icon svg {
      width: 36px;
      height: 36px;
      stroke: #16a34a;
      fill: none;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    .success-card h2 {
      font-size: 1.4rem;
      font-weight: 800;
      margin-bottom: 8px;
    }

    .success-card p {
      font-size: .9rem;
      color: var(--gray3);
      max-width: 300px;
      margin: 0 auto 20px;
    }

    .btn-back {
      display: inline-block;
      padding: 10px 24px;
      background: var(--red);
      color: var(--white);
      border-radius: 12px;
      font-weight: 700;
      text-decoration: none;
      font-size: .875rem;
    }

    /* ── SECTIONS ── */
    .section {
      padding: 80px 40px;
    }

    .section-inner {
      max-width: 1200px;
      margin: 0 auto;
    }

    @media(max-width:768px) {
      .section {
        padding: 60px 20px;
      }
    }

    .section-label {
      font-size: .75rem;
      font-weight: 700;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: var(--red);
      margin-bottom: 12px;
    }

    .section-title {
      font-size: clamp(1.6rem, 3vw, 2.25rem);
      font-weight: 800;
      color: var(--black);
      margin-bottom: 12px;
      line-height: 1.25;
    }

    .section-desc {
      font-size: 1rem;
      color: var(--gray3);
      max-width: 560px;
      line-height: 1.7;
    }

    /* ── WHY ── */
    .why-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 32px;
      margin-top: 52px;
    }

    @media(max-width:860px) {
      .why-grid {
        grid-template-columns: 1fr 1fr;
      }
    }

    @media(max-width:520px) {
      .why-grid {
        grid-template-columns: 1fr;
      }
    }

    .why-card {
      border: 1px solid var(--gray5);
      border-radius: var(--radius-lg);
      padding: 32px 28px;
      transition: box-shadow .2s, transform .2s;
    }

    .why-card:hover {
      box-shadow: 0 8px 32px rgba(0, 0, 0, .08);
      transform: translateY(-3px);
    }

    .why-icon {
      width: 56px;
      height: 56px;
      border-radius: 14px;
      background: var(--gray6);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
    }

    .why-icon svg {
      width: 26px;
      height: 26px;
      stroke: var(--red);
      fill: none;
      stroke-width: 1.8;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    .why-card h3 {
      font-size: 1rem;
      font-weight: 700;
      color: var(--black);
      margin-bottom: 10px;
    }

    .why-card p {
      font-size: .925rem;
      color: var(--gray3);
      line-height: 1.65;
    }

    /* ── HOW ── */
    .how-bg {
      background: var(--gray6);
    }

    .how-steps {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 0;
      margin-top: 52px;
      position: relative;
    }

    .how-steps::before {
      content: '';
      position: absolute;
      top: 38px;
      left: calc(12.5% + 20px);
      right: calc(12.5% + 20px);
      height: 2px;
      background: var(--gray5);
    }

    @media(max-width:860px) {
      .how-steps {
        grid-template-columns: repeat(2, 1fr);
        gap: 32px;
      }

      .how-steps::before {
        display: none;
      }
    }

    @media(max-width:500px) {
      .how-steps {
        grid-template-columns: 1fr;
      }
    }

    .how-step {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      padding: 0 16px;
    }

    .how-step-num {
      width: 76px;
      height: 76px;
      border-radius: 50%;
      background: var(--white);
      border: 2px solid var(--gray5);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
      position: relative;
      z-index: 1;
      box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
    }

    .how-step-num svg {
      width: 32px;
      height: 32px;
      stroke: var(--red);
      fill: none;
      stroke-width: 1.8;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    .how-step h4 {
      font-size: .9rem;
      font-weight: 700;
      color: var(--black);
      margin-bottom: 8px;
    }

    .how-step p {
      font-size: .875rem;
      color: var(--gray3);
      line-height: 1.6;
    }


    /* ── FAQ ── */
    .faq-list {
      margin-top: 48px;
      max-width: 860px;
    }

    .faq-item {
      border: 1px solid #f3f4f6;
      border-radius: 16px;
      overflow: hidden;
      margin-bottom: 12px;
      transition: border-color .2s;
    }

    .faq-item.open {
      border-color: #fca5a5;
    }

    .faq-btn {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      padding: 20px 24px;
      background: var(--white);
      border: none;
      cursor: pointer;
      text-align: left;
    }

    .faq-q {
      font-size: 1rem;
      font-weight: 600;
      color: var(--black);
    }

    .faq-item.open .faq-q {
      color: #dc2626;
    }

    .faq-icon {
      width: 28px;
      height: 28px;
      min-width: 28px;
      border-radius: 8px;
      background: var(--gray6);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background .2s;
    }

    .faq-icon svg {
      width: 16px;
      height: 16px;
      stroke: var(--gray2);
      fill: none;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
      transition: transform .3s;
    }

    .faq-item.open .faq-icon {
      background: #fee2e2;
    }

    .faq-item.open .faq-icon svg {
      stroke: #dc2626;
      transform: rotate(45deg);
    }

    .faq-body {
      max-height: 0;
      overflow: hidden;
      transition: max-height .35s ease;
    }

    .faq-item.open .faq-body {
      max-height: 300px;
    }

    .faq-body p {
      font-size: .875rem;
      color: var(--gray2);
      line-height: 1.75;
      padding: 0 24px 20px;
    }

    /* ── FOOTER ── */
    .footer {
      background: #111;
      color: var(--white);
      padding: 60px 40px 32px;
    }

    .footer-inner {
      max-width: 1200px;
      margin: 0 auto;
    }

    .footer-top {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 40px;
      margin-bottom: 48px;
    }

    @media(max-width:760px) {
      .footer-top {
        grid-template-columns: 1fr;
        gap: 32px;
      }
    }

    .footer-logo {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 14px;
      text-decoration: none;
    }

    .footer-logo img {
      height: 30px;
      filter: brightness(0) invert(1);
    }

    .footer-logo span {
      font-size: 1.25rem;
      font-weight: 800;
      color: var(--white);
    }

    .footer-brand p {
      font-size: .82rem;
      color: #888;
      line-height: 1.65;
      max-width: 260px;
    }

    .footer-socials {
      display: flex;
      gap: 12px;
      margin-top: 20px;
    }

    .footer-social {
      width: 36px;
      height: 36px;
      border-radius: 8px;
      background: rgba(255, 255, 255, .08);
      border: 1px solid rgba(255, 255, 255, .1);
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      transition: background .2s;
    }

    .footer-social:hover {
      background: var(--red);
    }

    .footer-social svg {
      width: 16px;
      height: 16px;
      fill: var(--white);
    }

    .footer-col h4 {
      font-size: .8rem;
      font-weight: 700;
      color: #888;
      letter-spacing: .1em;
      text-transform: uppercase;
      margin-bottom: 16px;
    }

    .footer-col ul {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .footer-col ul a {
      font-size: .875rem;
      color: #aaa;
      text-decoration: none;
      transition: color .2s;
    }

    .footer-col ul a:hover {
      color: var(--white);
    }

    .footer-bottom {
      border-top: 1px solid rgba(255, 255, 255, .08);
      padding-top: 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 16px;
    }

    .footer-bottom p {
      font-size: .8rem;
      color: #555;
    }

    .footer-apps {
      display: flex;
      gap: 12px;
    }

    .footer-app {
      display: flex;
      align-items: center;
      gap: 8px;
      background: rgba(255, 255, 255, .06);
      border: 1px solid rgba(255, 255, 255, .1);
      border-radius: 8px;
      padding: 7px 14px;
      text-decoration: none;
      transition: background .2s;
    }

    .footer-app:hover {
      background: rgba(255, 255, 255, .12);
    }

    .footer-app svg {
      width: 18px;
      height: 18px;
      fill: var(--white);
    }

    .footer-app div {
      text-align: left;
    }

    .footer-app span {
      display: block;
      font-size: .65rem;
      color: #888;
    }

    .footer-app strong {
      display: block;
      font-size: .78rem;
      color: var(--white);
      font-weight: 600;
    }
  </style>
</head>

<body>

  <nav class="navbar">
    <a href="index.html" class="navbar-logo">
      <img src="images/Canzi-Logo2 (1) (1).png" alt="Canzi"
        onerror="this.style.display='none'">
    </a>
  </nav>

  <section class="hero">

    <!-- LEFT — form panel -->
    <div class="hero-form-panel">
      <div class="form-card">
        <?php if ($success): ?>
          <div class="success-card">
            <div class="success-icon">
              <svg viewBox="0 0 24 24">
                <polyline points="20 6 9 17 4 12" />
              </svg>
            </div>
            <h2>Request sent!</h2>
            <p>Our team will contact you within 24–48 hours to finalize your registration.</p>

            <a href="http://127.0.0.1:5500/index.html" class="btn-back">
               Back to Home
            </a>
          </div>
        <?php else: ?>

          <h2 class="form-card-title">Become a Canzi Partner</h2>
          <p class="form-card-sub">Fill out the form, our team will contact you within 24 hours.</p>
          <?php if (!empty($errors)): ?>
            <div class="errors-box">
              <?php foreach ($errors as $e): ?>
                <p><?= htmlspecialchars($e) ?></p>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="partner.php" novalidate>

            <!-- Type de commerce -->
            <div class="field">
              <label>Business Type <span class="req">*</span></label>
              <div class="type-pills">
                <?php
                $pill_icons = [
                  'Restaurant'   => '<path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><line x1="7" y1="2" x2="7" y2="11"/><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V9a2 2 0 0 1 2-2h9"/><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>',
                  'Café / Snack' => '<path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>',
                  'Supermarché'  => '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>',
                  'Pharmacie'    => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
                  'Boutique'     => '<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>',
                  'Autre'        => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
                ];
                $sel_type = $_POST['business_type'] ?? '';
                foreach ($types as $t): ?>
                  <label class="type-pill">
                    <input type="radio" name="business_type" value="<?= $t ?>" <?= $sel_type === $t ? 'checked' : '' ?>>
                    <span class="type-pill-label">
                      <svg viewBox="0 0 24 24"><?= $pill_icons[$t] ?? '' ?></svg>
                      <?= $t ?>
                    </span>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Nom du commerce -->
            <div class="field">
              <label>Business Name <span class="req">*</span></label>
              <input type="text" name="business_name" placeholder="Ex: Al Baraka Restaurant"
                value="<?= htmlspecialchars($_POST['business_name'] ?? '') ?>">
            </div>

            <!-- Ville + Téléphone commerce -->
            <div class="field field-row">
              <div>
                <label>City <span class="req">*</span></label>
                <select name="city">
                  <option value="">choose ...</option>
                  <?php $sel_c = $_POST['city'] ?? '';
                  foreach ($cities as $c): ?>
                    <option value="<?= $c ?>" <?= $sel_c === $c ? 'selected' : '' ?>><?= $c ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div>
                <label>Business Phone</label>
                <input type="tel" name="business_phone" placeholder="+212 6XX XXX XXX"
                  value="<?= htmlspecialchars($_POST['business_phone'] ?? '') ?>">
              </div>
            </div>

            <!-- Adresse -->
            <div class="field">
              <label>Business Address</label>
              <input type="text" name="business_address" placeholder="Street, district, city"
                value="<?= htmlspecialchars($_POST['business_address'] ?? '') ?>">
            </div>

            <!-- Nom + Tel responsable -->
            <div class="field field-row">
              <div>
                <label>Owner Name <span class="req">*</span></label>
                <input type="text" name="owner_name" placeholder="Your full name"
                  value="<?= htmlspecialchars($_POST['owner_name'] ?? '') ?>">
              </div>
              <div>
                <label>Personal Phone <span class="req">*</span></label>
                <input type="tel" name="phone_number" placeholder="+212 6XX XXX XXX"
                  value="<?= htmlspecialchars($_POST['phone_number'] ?? '') ?>">
              </div>
            </div>

            <!-- Notes -->
            <div class="field">
              <label>Message (optional)</label>
              <textarea name="user_notes" placeholder="Tell us about your business..."><?= htmlspecialchars($_POST['user_notes'] ?? '') ?></textarea>
            </div>

            <!-- CHECKBOXES -->
            <div class="chk-group">
              <label class="chk-item wa-chk">
                <input type="checkbox" name="whatsapp_optin">
                <span class="chk-label">
                  I would like to receive updates and promotions via WhatsApp
                  <span class="wa-badge">
                    <svg viewBox="0 0 24 24">
                      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                    WhatsApp
                  </span>
                </span>
              </label>
              <label class="chk-item prv-chk">
                <input type="checkbox" name="privacy" id="privacy"
                  <?= isset($_POST['privacy']) ? 'checked' : '' ?>>
                <span class="chk-label">
                  I accept the <a href="http://127.0.0.1:5500/privacy.html" target="_blank">Privacy Policy</a> <strong style="color:var(--red)">*</strong>
                  <?php if (in_array('Vous devez accepter la Politique de confidentialité.', $errors)): ?>
                    <br><span class="field-error">This field is required.</span>
                  <?php endif; ?>
                </span>
              </label>
            </div>

            <button type="submit" class="btn-submit">
               Register
            </button>

          </form>
        <?php endif; ?>
      </div>
    </div>

    <div class="hero-img-panel">
      <img src="images/partner.png" alt="Partenaire Canzi">
      <div class="hero-img-overlay"></div>
      <div class="hero-img-text">
        <h1 class="hero-title">
          Join Canzi<br>and grow<br>your <span>business!</span>
        </h1>

        <p class="hero-subtitle">
          Attract more customers, increase your revenue, and grow your business with the leading delivery platform in Southern Morocco.
        </p>
      </div>
    </div>

  </section>

  <section class="section">
    <div class="section-inner">
      <p class="section-label">Why join us</p>
      <h2 class="section-title">Why become a Canzi partner?</h2>

      <p class="section-desc">
        Join the leading delivery platform in Southern Morocco and benefit from powerful tools to grow your business.
      </p>
      <div class="why-grid">
        <div class="why-card">
          <div class="why-icon">
            <svg viewBox="0 0 24 24">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
              <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
          </div>
          <h3>Reach more customers
          </h3>
          <p>
            Thousands of customers in your area are waiting for you. We help you deliver faster and build customer loyalty.</p>
        </div>
        <div class="why-card">
          <div class="why-icon">
            <svg viewBox="0 0 24 24">
              <line x1="12" y1="1" x2="12" y2="23" />
              <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
            </svg>
          </div>
          <h3>Earn more money</h3>
          <p>Serve more customers without expanding your space. Fast and transparent payments every two weeks.
          </p>
        </div>
        <div class="why-card">
          <div class="why-icon">
            <svg viewBox="0 0 24 24">
              <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
              <polyline points="16 7 22 7 22 13" />
            </svg>
          </div>
          <h3>Grow your business</h3>
          <p>
            Increase your sales, reach more customers, and make your business more visible. When you succeed, we succeed too.
          </p>
        </div>
        <div class="why-card">
          <div class="why-icon">
            <svg viewBox="0 0 24 24">
              <rect x="3" y="3" width="7" height="7" />
              <rect x="14" y="3" width="7" height="7" />
              <rect x="14" y="14" width="7" height="7" />
              <rect x="3" y="14" width="7" height="7" />
            </svg>
          </div>
          <h3>Full dashboard</h3>
          <p>Track your sales, manage your orders, and invest in marketing from your personalized merchant dashboard.
          </p>
        </div>
        <div class="why-card">
          <div class="why-icon">
            <svg viewBox="0 0 24 24">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
            </svg>
          </div>
          <h3>Secure payments</h3>
          <p>All payments are managed and secured by Canzi. You receive your money directly, with no stress.</p>
        </div>
        <div class="why-card">
          <div class="why-icon">
            <svg viewBox="0 0 24 24">
              <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
            </svg>
          </div>
          <h3>Dedicated support
          </h3>
          <p> A dedicated account manager supports you at every step. We are here to help you succeed.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="section how-bg">
    <div class="section-inner">
      <p class="section-label">How it works</p>
      <h2 class="section-title">How will we work together?</h2>
      <div class="how-steps">
        <div class="how-step">
          <div class="how-step-num">
            <svg viewBox="0 0 24 24">
              <circle cx="9" cy="21" r="1" />
              <circle cx="20" cy="21" r="1" />
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
            </svg>
          </div>
          <h4>Customer orders</h4>
          <p>The customer places an order through the Canzi app in a few seconds.</p>
        </div>
        <div class="how-step">
          <div class="how-step-num">
            <svg viewBox="0 0 24 24">
              <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2" />
              <line x1="7" y1="2" x2="7" y2="11" />
              <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V9a2 2 0 0 1 2-2h9" />
            </svg>
          </div>
          <h4>You prepare
          </h4>
          <p>You receive a notification and start preparing the order.
          </p>
        </div>
        <div class="how-step">
          <div class="how-step-num">
            <svg viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="10" />
              <polyline points="12 6 12 12 16 14" />
            </svg>
          </div>
          <h4>Canzi delivers</h4>
          <p>A Canzi driver picks up the order and delivers it quickly to the customer.
            .</p>
        </div>
        <div class="how-step">
          <div class="how-step-num">
            <svg viewBox="0 0 24 24">
              <polyline points="22 7 13.5 15.5 8.5 10.5 2 17" />
              <polyline points="16 7 22 7 22 13" />
            </svg>
          </div>
          <h4>Your business grows
          </h4>
          <p>Track your sales, manage your orders, and grow your business.</p>
        </div>
      </div>
    </div>
  </section>


  <section class="section" id="faq">
    <div class="section-inner">
      <p class="section-label">FAQ</p>
      <h2 class="section-title">Questions? We have answers.</h2>
      <div class="faq-list">
        <?php
        $faqs = [
          [
            'Why become a Canzi partner?',
            'Canzi helps businesses increase sales, reach new customers, and improve online visibility. By joining us, you gain access to thousands of customers who want to order from your store for delivery or pickup.'
          ],
          [
            'How to become a partner?',
            'Fill out the form at the top of this page with a valid phone number and email address. Our team will contact you within 24 hours to complete your registration and help you get started.'
          ],

          [
            'Why are there commission fees?',
            'The commission helps us fairly compensate delivery drivers and maintain platform services such as online ordering, secure payments, driver insurance, customer support, and continuous app improvements.'
          ],
          [
            'How will I receive orders?',
            'Canzi provides you with a merchant app (tablet or smartphone) that allows you to receive and manage orders in real time.'
          ],
          [
            'Can I pause orders?',
            'Yes, you can pause order reception at any time from your partner dashboard, for a fixed or unlimited duration.'
          ],
          [
            'How often do I get paid?',
            'Your first payout is made 30 days after going live, then you receive payments every two weeks directly to your bank account.'
          ],
        ];
        foreach ($faqs as $i => [$q, $a]):
        ?>
          <div class="faq-item <?= $i === 0 ? 'open' : '' ?>">
            <button class="faq-btn" onclick="toggleFaq(this)">
              <span class="faq-q"><?= $q ?></span>
              <span class="faq-icon">
                <svg viewBox="0 0 24 24">
                  <path d="M12 5v14M5 12h14" />
                </svg>
              </span>
            </button>
            <div class="faq-body">
              <p><?= $a ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>



  <script>
    function toggleFaq(btn) {
      const item = btn.closest('.faq-item');
      const isOpen = item.classList.contains('open');
      document.querySelectorAll('.faq-item.open').forEach(el => el.classList.remove('open'));
      if (!isOpen) item.classList.add('open');
    }
  </script>
</body>

</html>