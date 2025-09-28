<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8">
  <link rel="icon" type="image/png" href="favicon-64.png">
  <title>Todo list</title>
  <style>
    html {
      background: url("background-60.jpg") no-repeat center center fixed;
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
    }

    body {
      /*
      padding: 0;
      margin: 0;
      background: url('background.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      */
      background-color: transparent !important;
    }
    
    .div-fixed {
      position: fixed; /* Questo lo blocca rispetto alla viewport */
      top: 15px;       /* Distanza dal bordo superiore */
      right: 15px;     /* Distanza dal bordo destro */
      width: 60px;    /* Larghezza del div */
      height: 60px;   /* Altezza del div */
      color: black;    /* Colore del testo */
      display: flex;
      justify-content: center;
      align-items: center;
      /*background-color: #007bff; /* Colore di sfondo */
      /*border-radius: 8px; /* Bordi arrotondati */
      /*box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Ombra per un effetto 3D */
      z-index: 1000;   /* Assicura che sia sopra altri elementi */
      padding: 10px;
      text-align: center;
      opacity: 0;
    }

    .align-right {
      text-align: right;
    }
    
    @media (max-width: 768px) { /* Adjust this breakpoint as needed */
      .hide-on-mobile {
        display: none;
      }
    }

    .drag-over {
      border-top: 4px solid black !important;
    }
    .drag-over-first {
      border-bottom: 4px solid black !important;
    }
  </style>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>