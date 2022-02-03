<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>" >
    <div class="input-group">
        <input type="text" value="<?php echo $_GET['s']; ?>" name="s" placeholder="Quem procura acha">
        <div class="input-group-append">
            <button class="btn-search" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
</form>

