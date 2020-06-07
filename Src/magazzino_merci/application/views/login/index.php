<body id="login-background">
<div class="login-bg-image container col-md-5 vertical-align">
    <form class="text-center border border-light p-5 white" action='<?php echo URL . '/home/login' ?>' method='POST'>

        <p class="h4 mb-4">Effettua l'accesso</p>

        <p>Inserisci le tue credenziali per accedere al sistema</p>

        <!-- email -->
        <input type="email" class="form-control mb-4" placeholder="example@example.com" name="email" required>

        <!-- Email -->
        <input type="password" class="form-control mb-4" placeholder="Password" name="password" required>

        <!-- Sign in button -->
        <button class="btn btn-danger btn-block" type="submit">Accedi</button>

    </form>
</div>
</body>