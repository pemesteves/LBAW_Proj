<a href="{{$report->link()}}" style="text-decoration:none; color:black">
    <div class="card report" style="margin:2% 15%; padding-bottom:0rem" data-id="{{$report->report_id}}">
        <div class="card-body">
            <div style="display:flex;justify-content:space-between">
                <h5 class="card-title">{{$report->title}}</h5>
                <h6 class="card-subtitle mb-2 text-muted">{{$report->referenceTo()}} | {{date('d-m-Y H:m', strtotime($report->date))}}</h6>
            </div>
            <div style="display:flex;justify-content:space-between">
                <h6 class="card-text">Issue : {{$report->reason}}</h6>
                <div>
                    @if(!$report->approval)
                        <button type="button" class="btn btn-success accept">Accept</button>
                        <button type="button" class="btn btn-danger decline" style="margin-top:0.2rem">Ignore</button>
                    @else
                        <button type="button" class="btn btn-danger decline" style="margin-top:0.2rem">Cancel</button>
                    @endif
                </div>
            </div>

        </div>
    </div>
</a>