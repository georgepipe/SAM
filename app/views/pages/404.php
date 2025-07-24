<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
 
<style>

body,
h1,
h2,
h3,
h4,
p,
a { color: #e0e2f4; }

body,
p { font: normal #{$base-font-size}/1.25rem; color: white;}
h1 { font: normal 2.75rem/1.05em $font-stack; }
h2 { font: normal 2.25rem/1.25em $font-stack; }
h3 { font: lighter 1.5rem/1.25em $font-stack; }
h4 { font: lighter 1.125rem/1.2222222em $font-stack; }

body { background: #0414a7; font-family: "Press Start 2P" }

.container {
  width: 90%;
  margin: auto;
  max-width: 640px;
}

.bsod {
  padding-top: 10%;
  
  .neg {
    text-align: center;
    color: #0414a7;
    
    .bg {
      background: #aaaaaa;
      padding: 0 15px 2px 13px;
    }
  }
  .title { margin-bottom: 50px; }
  .nav {
    margin-top: 35px;
    text-align: center;
    
    .link {
      text-decoration: none;
      padding: 0 9px 2px 8px;
      
      &:hover,
      &:focus {
        background: #aaaaaa;
        color: #0414a7;
      }
    }
  }
}
</style>



<main class="bsod container">
  <h1 class="neg title"><span class="bg">Error - 404</span></h1>
  <p>This page or pdf cannot be found. You can:</p>
  <p>* Check emails for the avn.pdfs.<br />
  * Request the avn.pdf from luke@funktion-one.co.uk.</br>
  * Contact your system admin. <br />
  * Pray to the almighty spagetti monster.

</p>
</main>
 
</body>
</html>