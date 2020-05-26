<a href="{{$request->link()}}" style="text-decoration:none; color:black">
    <div class="card request" style="background-color:powderblue;margin:2% 15%; padding-bottom:0rem" data-id="{{$request->request_id}}">
        <div class="card-body">
            <div style="display:flex;justify-content:space-between">
                <h5 class="card-title">Verification request {{$request->request_id}}</h5>
                <h6 class="card-subtitle mb-2 text-muted">{{date('d-m-Y H:m', strtotime($request->date))}}</h6>
            </div>
            <div style="display:flex;justify-content:space-between">
                <h6 class="card-text">Issue : {{$request->reason}}</h6>
                <div>
                    @if(strcmp($request->type,'pending') == 0)
                        <button type="button" class="btn btn-success accept">Accept</button>
                        <button type="button" class="btn btn-danger decline" style="margin-top:0.2rem">Ignore</button>
                    @elseif(strcmp($request->type,'accepted') == 0)
                        <button type="button" class="btn btn-danger decline" style="margin-top:0.2rem">Cancel</button>
                    @endif
                </div>
            </div>

        </div>
    </div>
</a>