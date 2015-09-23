@if($suggestions_data)
  <div class="well-snow">
    <h4 class="nmt"><span class="text-success" data-icon="&#xe002;"></span> Some people you may know</h4>
    <hr class="hr-dashed">
    {{ $suggestions_data }}
  </div>
@endif