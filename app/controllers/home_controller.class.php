<?php

class HomeController {
  public function render() { }
}

// Controller
class Controller extends AppController {
  protected function init() { }
}


$controller = new Controller();

// Extract Main Controller Vars
extract($controller->view->vars);

?>

  <main>
    <div>
      <img src="/images/butterfly_blue.png" alt="">
      <article>
        <a href="http://en.wikipedia.org/wiki/Spaced_repetition">Spaced
          repetition</a> uses increasing intervals of time between 
          reviews of previously learned material which promotes higher
          memorization.
        <section class="highlighted-text">Spaced Repetition for Kids</section>
        For children who want to learn quickly. For children who need a
        little extra help. For parents who want some organization.
      </article>
      <img src="/images/landscape_bugs_blue.png" alt="">
      <footer>Jon Nyman<br>&copy; 2014</footer>
    </div>

    <div>

      <div class="form-panel">
        <h1 class="highlighted-text sub-heading">Sign in - Sign up</h1>
        <p>Enter your information below and get started!</p>
        <form action="/api/login" method="POST">
          <input type="email" placeholder="enter e-mail here" name="email" title="E-mail" data-exp-name="email">
          <input type="password" placeholder="enter password here" name="password" data-exp-name="password" title="Password">
          <input type="hidden" name="user" value="user">
          <button type="submit">Login</button>
        </form>
        <div class="description-boxes">
          <div class="short-description">
            <h4 class="highlighted-text">For Kids</h4>
            <p>It's made specifically for children.</p>
          </div>
          <div class="short-description">
            <h4 class="highlighted-text">Customize</h4>
            <p>Add or remove any cards that your child(ren) needs.</p>
          </div>
        </div>
        <p>A learning tool for young children.</p>
      </div>

      <div class="highlight-panel">
        <h1  class="highlighted-text sub-heading">
          All About Spaced Repetition
        </h1>

        <p>The idea of spaced repetition was first proposed in a paper of Psychology of Study by C. A. Mace in 1932.</p>
        <div class="description-boxes">
          <div class="short-description">
            <h4 class="highlighted-text">Fill the Gaps</h4>
            <p>Help your child catch up to missed learning opportunities.</p>
          </div>
          <div class="short-description">
            <h4 class="highlighted-text">Get Ahead</h4>
            <p>Spaced learning is fast and sticks with you.</p>
          </div>
        </div>
        <p>Spaced repetition gained more traction in 1980 with the advent of the personal computer.</p>

      </div>

    </div>

  </main>

  <script src="/bower_components/jquery/dist/jquery.js"></script>
  <script src="/bower_components/ReptileForms/dist/reptileforms.js"></script>
  <script>
    var form = new ReptileForm('form');
  </script>
