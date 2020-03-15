<?php
    include_once("template_common.php");
?>

<?php function draw_login()
{
    /**
     * Draws the login page
     */?>
    <section id="login" class="container d-flex justify-content-center flex-column align-items-center">
        <form class="d-flex flex-column justify-content-center">
            <div class="form-group">
                <input type="email" required class="form-control" id="email" placeholder="Email" style="border-radius: 1em; margin:1em 0;">
                <br>
                <input type="password" required class="form-control" id="password" placeholder="Password" style="border-radius: 1em; margin:1em 0;">


            </div>
            <button type="submit" class="btn btn-primary" action="TO UPDATE" method="POST" style="color:whitesmoke">Login</button>

            
        </form>

        <footer>
                <p>Don't have an account? Then <a href="/register.php" style="color: #ffa31a">REGISTER</a></p>
                <p>Forgot your password? <a href="TO UPDATE" style="color: #ffa31a">Recover password</a></p>

        </footer>
    </section>

<?php } ?>


<?php function draw_signup()
{
    /**
     * Draws the sign up page
     */?>
     <section id="register" class="container d-flex justify-content-center flex-column align-items-center">
        <form class="d-flex flex-column justify-content-center">
            <div class="form-group">
                <input type="text" required class="form-control" id="name" required="required" placeholder="Name" style="border-radius: 1em; margin:1em 0;">
                
                <input type="email" required class="form-control" id="email" placeholder="Email" style="border-radius: 1em; margin:1em 0;">
                
                <input type="password" required class="form-control" id="password" placeholder="Password" style="border-radius: 1em; margin:1em 0;">
                
                <input type="password" required class="form-control" id="confpassword" placeholder="Confirm Password" style="border-radius: 1em; margin:1em 0;">
                
                <input type="text" required class="form-control" id="university" placeholder="University" style="border-radius: 1em; margin:1em 0;">
                


            </div>
            <div class="form-group">
                <select class="form-control" required id="occupation" placeholder="Occupation" style="border-radius: 1em; margin:1em 0;">
                <option>Choose an option:</option>
                <option>Student</option>
                <option>Teacher</option>
                <option>Organization</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" action="TO UPDATE" method="POST">Register</button>
        </form>
        </body>
        <footer>
            <p>Already have an account? Then <a href="/login.php" style="color: #ffa31a">LOGIN</a></p>
        </footer>
    </section>
        
    


<?php } ?>