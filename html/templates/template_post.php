<?php function draw_post_card(){ ?>
    <div class="card mb-3" style="max-width:70%;margin:5% 15%">
        <div class="row no-gutters">
            <div class="col-sm" >
                <div class="card text-center" style="border-bottom:none;border-top:none;border-radius:0;height:100%;">
                    <img src="https://www.pluspixel.com.br/wp-content/uploads/avatar-7.png" class="card-img-top mx-auto d-block" alt="..." style="border-radius:50%; max-width:5rem; padding-top:0.8rem">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" style="border:none;padding-top:0.2rem;padding-bottom:0.2rem">Joaquin</li>
                        <li class="list-group-item" style="border:none;padding-top:0.2rem;padding-bottom:0.2rem">FEUP</li>
                        <li class="list-group-item" style="border:none;padding-top:0.2rem;padding-bottom:0.2rem">4 friends</li>
                    </ul>
                    <div class="card-body" style="padding-top:0.2rem;padding-bottom:0.2rem">
                        <p class="card-text" style="margin-bottom:0rem"><small class="text-muted" style="margin-bottom:0rem">04-03-2020</small></p>
                        <p class="card-text"><small class="text-muted" style="margin-bottom:0.2rem">18:40</small></p>
                    </div>
                </div>
            </div>
            <div class="col-md-8" style="flex-grow:1; max-width:100%">
                <div class="card" style="height: 100%">
                    <div class="card-body">
                        <h3 class="card-title">Card title</h3>
                        <p class="card-text">

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla accumsan facilisis euismod. Nullam pellentesque velit sit amet mauris mollis sollicitudin non vitae felis. Vestibulum elementum, augue vel maximus mollis, ipsum enim scelerisque sapien, interdum mollis nulla elit et neque. Pellentesque arcu ante, fermentum eu lectus at, hendrerit fermentum lacus. Vestibulum vel turpis non elit finibus dictum. Aliquam accumsan sit amet nunc quis condimentum. Donec et turpis sit amet lectus pharetra tincidunt. Praesent sagittis diam ac quam sagittis, non viverra mi consectetur. In volutpat ultrices urna sit amet dapibus. Suspendisse tincidunt justo vitae quam blandit pharetra. Pellentesque accumsan sit amet purus sed ornare. Cras risus enim, pellentesque et sem non, posuere lacinia turpis. Sed ante lorem, tincidunt sed erat id, mollis semper mi. Vestibulum cursus auctor consectetur. Ut pharetra dolor ligula, ac aliquet felis egestas sed.

Quisque a suscipit purus. Sed in ultricies tellus. Fusce sit amet gravida libero, vitae tristique magna. Maecenas volutpat, ante eget aliquet imperdiet, libero mauris gravida risus, nec semper neque mi ac quam. Pellentesque ac metus accumsan, ornare turpis consequat, ullamcorper quam. Etiam convallis odio et purus congue, consectetur sagittis eros congue. Pellentesque ornare dui non magna dictum feugiat. Morbi lacus sapien, rhoncus sed ante nec, elementum laoreet velit. Sed vestibulum consequat erat et lobortis. Vestibulum ullamcorper elit id hendrerit feugiat. Nulla fermentum tristique justo. Nunc ac pharetra libero. Quisque felis diam, imperdiet id accumsan nec, eleifend eget quam.

Nullam tempor a libero eu eleifend. Integer accumsan, sem et gravida iaculis, est nibh feugiat orci, non faucibus orci ipsum ac quam. Curabitur sit amet imperdiet ligula, quis sodales dui. Fusce venenatis arcu a suscipit gravida. In iaculis ante felis, eu dapibus odio maximus in. Donec facilisis consectetur mi, quis faucibus felis euismod id. Donec ultricies mauris sit amet placerat maximus. Sed nec ornare purus, sed facilisis nisl. Curabitur laoreet consequat nisi non dictum.

Nulla porttitor augue sapien, vitae volutpat augue semper sit amet. In lorem nisi, volutpat eu egestas at, gravida non libero. Integer vitae turpis id quam luctus rutrum et eu lorem. Cras eu malesuada quam. Praesent sit amet neque non arcu porttitor dictum. Sed malesuada id orci ut rhoncus. Nulla lacinia odio ut ipsum suscipit elementum. Morbi elementum eu arcu sit amet mollis. Integer eget tristique mi. Curabitur placerat, nisi id pulvinar sagittis, dui lacus dictum lorem, at maximus risus risus ac nisl. Integer vel condimentum magna. Aenean at vestibulum leo.

Maecenas suscipit quam quis diam hendrerit, id tincidunt nulla vulputate. Pellentesque semper diam et magna tincidunt, vel dictum metus iaculis. Phasellus varius odio ut orci vehicula, eget tempus urna ultricies. Etiam sed nunc magna. Pellentesque eu tortor vitae dui consequat accumsan nec non ante. Nam ut lorem quis mi luctus efficitur. Curabitur quis lobortis mauris, placerat porttitor nisi. Suspendisse potenti. Sed vitae dapibus lectus, scelerisque pellentesque orci. Morbi eget ipsum laoreet, elementum lorem quis, elementum diam. Aliquam eu rutrum nisi. Phasellus nisl nisl, ultricies nec dignissim non, porttitor quis odio. Mauris vel sodales nisi, eu tincidunt ex. Aenean quis dictum dolor. Duis gravida nunc et fermentum iaculis. Donec volutpat, ex id placerat tempus, sem orci malesuada nibh, vel viverra magna justo non nisl. </p>
                    </div>
                    <div class="card-footer" style="border-left:none;border-right:none;border-bottom:none">
                        <span class="comment"> 15 comments </span>
                        <div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } 

function draw_create_post(){ ?>
    <form method="post">
        <div class="container" style="max-width:70%;margin:5% 15%; border: 1px solid lightgrey; border-bottom-left-radius: .5em; border-bottom-right-radius: .5em;">
            <div class="row" style="padding:0; background-color: white; border: 0; border-bottom: 1px solid lightgrey;">
                <input type="text" placeholder="&ensp;&nbsp;Title" style="width: 100%; margin: 0 auto; border: 0; border-radius: inherit"></input>
            </div>
            <div class="row" style="border-radius: 0;">
                <textarea class="form-control" placeholder="Write here..." rows="3" style="border: 0; "></textarea>
            </div>
            <div class="row" style="background-color: white; border-top: 1px solid lightgrey; border-bottom-left-radius: .5em; border-bottom-right-radius: .5em;">
                <div class="col-11">

                </div>
                <div class="col-1" style="padding: 0">
                    <button type="submit" style="width: 100%; margin: 0; background-color: white; border: 0; border-left: 1px solid lightgrey; border-bottom-right-radius: .5em;"><span class="fa fa-caret-right"></span></button>
                </div>    
            </div>
        </div>
    </form>
<?php
}
?>