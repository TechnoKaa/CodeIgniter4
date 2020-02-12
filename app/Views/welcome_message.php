<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Welcome to CodeIgniter</title>
  <meta name="description" content="The small framework with powerful features">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" type="image/png" href="/favicon.ico"/>
</head>
<body>

<!-- STYLES ---------------------------------------------------------------- -->

<style {csp-style-nonce}>
  html, body {
    color: #212529;
    font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif, Apple Color Emoji, Segoe UI Emoji;
    font-size: 16px;
    margin: 0;
    padding: 0;
  }
  header {
    background-color: #f7f8f9;
  }
  header ul {
    list-style-type: none;
    margin: 0;
    overflow: hidden;
    padding: 0.75rem;
  }
  header li {
    float: right;
  }
  header li a {
    color: rgba(0,0,0,0.5);
    display: block;
    padding: 0.8rem;
    text-align: center;
    text-decoration: none;
  }
  header li a:hover {
    color: rgba(0,0,0,0.75);
  }
  header .logo {
    float: left;
    padding: 0 0.5rem;
  }
  header .menu-more {
    display: none;
    font-size: 2rem;
    font-weight: bold;
  }
  header .menu-more a {
    padding: 0.45rem 0.75rem;
  }
  header .heroe {
    margin: 4rem auto 0 auto;
    max-width: 1100px;
    padding: 0 1.75rem;
  }
  header .heroe h1 {
    font-size: 2.5rem;
    font-weight: 500;
  }
  header .heroe h2 {
    font-size: 1.5rem;
    font-weight: 300;
  }
  header .divider {
    background-image: url(data:image/png;base64,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);
    background-position: bottom;
    background-repeat: repeat-x;
    background-size: contain;
    min-height: 150px;
  }
  section {
    margin: 3rem auto 5rem auto;
    max-width: 1100px;
    padding: 0 1.75rem;
  }
  section pre {
    background-color: #f7f8f9;
    border: 1px solid #F0F0F0;
    -webkit-border-radius: 8px;
    -moz-border-radius: 8px;
    border-radius: 8px;
    display: block;
    font-size: 0.9rem;
    margin: 2rem 0;
    padding: 1rem 1.5rem;
    white-space: pre-wrap;
    word-break: break-all;
  }
  section code {
    display: block;
  }
  section a {
    color: #DD4814;
  }
  footer {
    background-color: #DD4814;
    font-weight: 200;
    text-align: center;
  }
  footer .environment {
    color: #FFFFFF;
    padding: 2.5rem 1.75rem;
  }
  footer .copyrights {
    background-color: #FFFFFF;
    color: #777777;
    font-size: 0.8rem;
    padding: 0.25rem 1.75rem;
  }
  @media (min-width: 640px) {
    header .menu-item {
      display: block!important;
    }
  }
  @media (max-width: 640px) {
    header ul {
      padding: 0 0.75rem;
    }
    header .logo {
      padding: 0.75rem 0.75rem;
    }
    header .menu-item {
      background-color: #FBFBFB;
      display: none;
      width: 100%;
    }
    header .menu-more {
      display: block;
    }
    header .divider {
      min-height: 100px;
    }
  }
</style>

<!-- HEADER: MENU + HEROE SECTION ------------------------------------------ -->

<header>

  <div class="menu">
    <ul>
      <li class="logo"><img height="38px" width="138px" alt="CodeIgniter" src="data:image/png;base64,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" alt=""></li>
      <li class="menu-more"><a target="_self" onclick="mobileMenu();">&#9776;</a></li>
      <li class="menu-item"><a href="https://github.com/codeigniter4/CodeIgniter4/blob/master/CONTRIBUTING.md" target="_blank">Contribution</a></li>
      <li class="menu-item"><a href="https://codeigniter4.github.io/userguide/" target="_blank">Documentation</a></li>
      <li class="menu-item"><a href="https://forum.codeigniter.com/" target="_blank">Community</a></li>
      <li class="menu-item"><a href="https://codeigniter.com" target="_blank">Website</a></li>
    </ul>
  </div>

  <div class="heroe">

    <h1>Welcome to CodeIgniter <?= CodeIgniter\CodeIgniter::CI_VERSION ?></h1>

    <h2>The small framework with powerful features</h2>

  </div>

  <div class="divider"></div>

</header>

<!-- CONTENT --------------------------------------------------------------- -->

<section>

  <p>The page you are looking at is being generated dynamically by CodeIgniter.</p>

  <p>If you would like to edit this page you will find it located at:</p>

  <pre><code>app/Views/welcome_message.php</code></pre>

  <p>The corresponding controller for this page can be found at:</p>

  <pre><code>app/Controllers/Home.php</code></pre>

  <p>If you are exploring CodeIgniter for the very first time, you should start by reading the <a href="https://codeigniter4.github.io/CodeIgniter4" target="_blank">User Guide</a>.</p>

</section>

<!-- FOOTER: DEBUG INFO + COPYRIGHTS --------------------------------------- -->

<footer>

  <div class="environment">

    <p>Page rendered in {elapsed_time} seconds</p>

    <p>Environment: <?= ENVIRONMENT ?></p>

  </div>

  <div class="copyrights">

    <p>&copy; <?= date('Y') ?> CodeIgniter Foundation. Codeigniter is open source project released under the MIT open source licence.</p>

  </div>

</footer>

<!-- SCRIPTS --------------------------------------------------------------- -->

<script type="text/javascript">
function mobileMenu() {
  var menuItems = document.getElementsByClassName('menu-item');
  for (var i = 0; i < menuItems.length; i ++) {
    if(menuItems[i].style.display === 'block') {
      menuItems[i].style.display = 'none';
    } else {
      menuItems[i].style.display = 'block';
    }
  }
}
</script>

<!-- ----------------------------------------------------------------------- -->

</body>
</html>
