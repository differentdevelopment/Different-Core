<!-- dependencyJson -->
@php
  $field['wrapper'] = $field['wrapper'] ?? $field['wrapperAttributes'] ?? [];
  $field['wrapper']['class'] = $field['wrapper']['class'] ?? 'form-group col-sm-12';
  $field['wrapper']['class'] = $field['wrapper']['class'].' checklist_dependency';
  $field['wrapper']['data-entity'] = $field['wrapper']['data-entity'] ?? $field['field_unique_name'];
  $field['wrapper']['data-init-function'] = $field['wrapper']['init-function'] ?? 'bpFieldInitChecklistDependencyElement';
@endphp

@include('crud::fields.inc.wrapper_start')

    <label>{!! $field['label'] !!}</label>

    <?php
        $entity_model = $crud->getModel();

        //short name for dependency fields
        $primary_dependency = $field['subfields']['primary'];
        $secondary_dependency = $field['subfields']['secondary'];

        //all items with relation
        $dependencies = $primary_dependency['model']::with($primary_dependency['entity_secondary'])->get();

        $dependencyArray = [];

        //convert dependency array to simple matrix ( primary id as key and array with secondaries id )
        foreach ($dependencies as $primary) {
            $dependencyArray[$primary->id] = [];
            foreach ($primary->{$primary_dependency['entity_secondary']} as $secondary) {
                $dependencyArray[$primary->id][] = $secondary->id;
            }
        }

      //for update form, get initial state of the entity
      if (isset($id) && $id) {

        //get entity with relations for primary dependency
          $entity_dependencies = $entity_model->with($primary_dependency['entity'])
          ->with($primary_dependency['entity'].'.'.$primary_dependency['entity_secondary'])
          ->find($id);

          $secondaries_from_primary = [];

          //convert relation in array
          $primary_array = $entity_dependencies->{$primary_dependency['entity']}->toArray();

          $secondary_ids = [];

          //create secondary dependency from primary relation, used to check what checkbox must be checked from second checklist
          if (old($primary_dependency['name'])) {
              foreach (old($primary_dependency['name']) as $primary_item) {
                  foreach ($dependencyArray[$primary_item] as $second_item) {
                      $secondary_ids[$second_item] = $second_item;
                  }
              }
          } else { //create dependencies from relation if not from validate error
              foreach ($primary_array as $primary_item) {
                  foreach ($primary_item[$secondary_dependency['entity']] as $second_item) {
                      $secondary_ids[$second_item['id']] = $second_item['id'];
                  }
              }
          }
      }

        //json encode of dependency matrix
        $dependencyJson = json_encode($dependencyArray);

        $primaryGroup = null;
        $secondaryGroup = null;
    ?>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <a class="nav-link active" id="{{ $primary_dependency['name'] }}-tab" data-toggle="tab" href="#{{ $primary_dependency['name'] }}" role="tab" aria-controls="{{ $primary_dependency['name'] }}" aria-selected="true">
          {{ $primary_dependency['label'] }}
        </a>
      </li>
      <li class="nav-item" role="presentation">
        <a class="nav-link" id="{{ $secondary_dependency['name'] }}-tab" data-toggle="tab" href="#{{ $secondary_dependency['name'] }}" role="tab" aria-controls="{{ $secondary_dependency['name'] }}" aria-selected="false">
          {{ $secondary_dependency['label'] }}
        </a>
      </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">

      <div class="tab-pane active px-4" id="{{ $primary_dependency['name'] }}" role="tabpanel" aria-labelledby="{{ $primary_dependency['name'] }}-tab">
        <div class="hidden_fields_primary" data-name="{{ $primary_dependency['name'] }}">
          <input type="hidden" bp-field-name="{{$primary_dependency['name']}}" name="{{$primary_dependency['name']}}" value="" />
          @if(isset($field['value']))
            @if(old($primary_dependency['name']))
                @foreach( old($primary_dependency['name']) as $item )
                <input type="hidden" class="primary_hidden" name="{{ $primary_dependency['name'] }}[]" value="{{ $item }}">
                @endforeach
            @else
                @foreach( $field['value'][0]->pluck('id', 'id')->toArray() as $item )
                <input type="hidden" class="primary_hidden" name="{{ $primary_dependency['name'] }}[]" value="{{ $item }}">
                @endforeach
            @endif
          @endif
        </div>
        <div class="row">
          @foreach ($primary_dependency['model']::orderBy($primary_dependency['order_by'])->get() as $connected_entity_entry)
            @if ($primary_dependency['hasGroup'] && $primaryGroup !== $connected_entity_entry[$primary_dependency['order_by']])
              @php
                  $primaryGroup = $connected_entity_entry[$primary_dependency['order_by']];
              @endphp
              <div class="col-12 mt-2">
                <div style="display: flex; flex-flow: row; align-items: flex-end; margin-bottom: 15px;">
                  <div style="font-size: 14px; font-weight: 700; display: inline-block; margin: 0; padding: 0 10px 0 0; text-transform: uppercase;">
                      {{ $primaryGroup }}
                  </div>
                  <div style="display: flex; flex: 1 1; background: #e5e5e5; height: 1px;"></div>
              </div>
              </div>
            @endif
            <div class="col-sm-{{ isset($primary_dependency['number_columns']) ? intval(12/$primary_dependency['number_columns']) : '4'}} mb-2">
              <div class="custom-control custom-checkbox">
                <input 
                  type="checkbox"
                  id="primary{{ $connected_entity_entry->id }}"
                  data-id="{{ $connected_entity_entry->id }}"
                  class="primary_list custom-control-input"
                  @foreach ($primary_dependency as $attribute => $value)
                    @if (is_string($attribute) && $attribute != 'value')
                      @if ($attribute=='name')
                        {{ $attribute }}="{{ $value }}_show[]"
                      @else
                        {{ $attribute }}="{{ $value }}"
                      @endif
                    @endif
                  @endforeach
                  value="{{ $connected_entity_entry->id }}"
                  @if( ( isset($field['value']) && is_array($field['value']) && in_array($connected_entity_entry->id, $field['value'][0]->pluck('id', 'id')->toArray())) || ( old($primary_dependency["name"]) && in_array($connected_entity_entry->id, old( $primary_dependency["name"])) ) )
                  checked="checked"
                  @endif >
                <label class="custom-control-label" for="primary{{ $connected_entity_entry->id }}" style="line-height: 1.3;">
                  <span class="m-0">
                    {{ $connected_entity_entry->{$primary_dependency['attribute']} }}
                    <br/>
                    <small class="text-primary">
                      {{ $connected_entity_entry->{$primary_dependency['secondary_attribute']} }}
                    </small>
                </span>
                </label>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <div class="tab-pane px-4" id="{{ $secondary_dependency['name'] }}" role="tabpanel" aria-labelledby="{{ $secondary_dependency['name'] }}-tab">
          <div class="hidden_fields_secondary" data-name="{{ $secondary_dependency['name'] }}">
            <input type="hidden" bp-field-name="{{$secondary_dependency['name']}}" name="{{$secondary_dependency['name']}}" value="" />
            @if(isset($field['value']))
              @if(old($secondary_dependency['name']))
                @foreach( old($secondary_dependency['name']) as $item )
                  <input type="hidden" class="secondary_hidden" name="{{ $secondary_dependency['name'] }}[]" value="{{ $item }}">
                @endforeach
              @else
                @foreach( $field['value'][1]->pluck('id', 'id')->toArray() as $item )
                  <input type="hidden" class="secondary_hidden" name="{{ $secondary_dependency['name'] }}[]" value="{{ $item }}">
                @endforeach
              @endif
            @endif
          </div>
          <div class="row">
            @foreach ($secondary_dependency['model']::orderBy($secondary_dependency['order_by'])->get() as $connected_entity_entry)
              @if ($secondary_dependency['hasGroup'] && $secondaryGroup !== $connected_entity_entry[$secondary_dependency['order_by']])
                @php
                    $secondaryGroup = $connected_entity_entry[$secondary_dependency['order_by']];
                @endphp
                <div class="col-12 mt-2">
                  <div style="display: flex; flex-flow: row; align-items: flex-end; margin-bottom: 15px;">
                    <div style="font-size: 14px; font-weight: 700; display: inline-block; margin: 0; padding: 0 10px 0 0; text-transform: uppercase;">
                        {{ $secondaryGroup }}
                    </div>
                    <div style="display: flex; flex: 1 1; background: #e5e5e5; height: 1px;"></div>
                </div>
                </div>
              @endif
              <div class="col-sm-{{ isset($secondary_dependency['number_columns']) ? intval(12/$secondary_dependency['number_columns']) : '4'}} mb-3">
                <div class="custom-control custom-checkbox">
                    <input 
                      type="checkbox"
                      id="secondary{{ $connected_entity_entry->id }}"
                      data-id="{{ $connected_entity_entry->id }}"
                      class="secondary_list custom-control-input"
                      @foreach ($secondary_dependency as $attribute => $value)
                          @if (is_string($attribute) && $attribute != 'value')
                            @if ($attribute=='name')
                              {{ $attribute }}="{{ $value }}_show[]"
                            @else
                              {{ $attribute }}="{{ $value }}"
                            @endif
                          @endif
                      @endforeach
                      value="{{ $connected_entity_entry->id }}"
                      @if( ( isset($field['value']) && is_array($field['value']) && (  in_array($connected_entity_entry->id, $field['value'][1]->pluck('id', 'id')->toArray()) || isset( $secondary_ids[$connected_entity_entry->id])) || ( old($secondary_dependency['name']) &&   in_array($connected_entity_entry->id, old($secondary_dependency['name'])) )))
                          checked = "checked"
                          @if(isset( $secondary_ids[$connected_entity_entry->id]))
                            disabled = disabled
                          @endif
                      @endif >
                    <label class="custom-control-label" for="secondary{{ $connected_entity_entry->id }}" style="line-height: 1.3;">
                      <span class="m-0">
                        {{ $connected_entity_entry->{$secondary_dependency['attribute']} }}
                          <br/>
                          <small class="text-primary">
                            {{ $connected_entity_entry->{$secondary_dependency['secondary_attribute']} }}
                          </small>
                      </span>
                  </label>
                </div>
              </div>
            @endforeach
        </div>
      </div>
    </div><!-- /.container -->


    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif

@include('crud::fields.inc.wrapper_end')

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}

@push('crud_fields_scripts')
    <script>
        var  {{ $field['field_unique_name'] }} = {!! $dependencyJson !!};
    </script>
@endpush

@if ($crud->checkIfFieldIsFirstOfItsType($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
    <!-- include checklist_dependency js-->
    <script>
      function bpFieldInitChecklistDependencyElement(element) {

          var unique_name = element.data('entity');
          var dependencyJson = window[unique_name];
          var thisField = element;

          thisField.find('.primary_list').change(function(){

            var idCurrent = $(this).data('id');
            if($(this).is(':checked')){

              //add hidden field with this value
              var nameInput = thisField.find('.hidden_fields_primary').data('name');
              var inputToAdd = $('<input type="hidden" class="primary_hidden" name="'+nameInput+'[]" value="'+idCurrent+'">');

              thisField.find('.hidden_fields_primary').append(inputToAdd);

              $.each(dependencyJson[idCurrent], function(key, value){
                //check and disable secondies checkbox
                thisField.find('input.secondary_list[value="'+value+'"]').prop( "checked", true );
                thisField.find('input.secondary_list[value="'+value+'"]').prop( "disabled", true );
                //remove hidden fields with secondary dependency if was set
                var hidden = thisField.find('input.secondary_hidden[value="'+value+'"]');
                if(hidden)
                  hidden.remove();
              });

            }else{
              //remove hidden field with this value
              thisField.find('input.primary_hidden[value="'+idCurrent+'"]').remove();

              // uncheck and active secondary checkboxs if are not in other selected primary.

              var secondary = dependencyJson[idCurrent];

              var selected = [];
              thisField.find('input.primary_hidden').each(function (index, input){
                selected.push( $(this).val() );
              });

              $.each(secondary, function(index, secondaryItem){
                var ok = 1;

                $.each(selected, function(index2, selectedItem){
                  if( dependencyJson[selectedItem].indexOf(secondaryItem) != -1 ){
                    ok =0;
                  }
                });

                if(ok){
                  thisField.find('input.secondary_list[value="'+secondaryItem+'"]').prop('checked', false);
                  thisField.find('input.secondary_list[value="'+secondaryItem+'"]').prop('disabled', false);
                }
              });

            }
          });


          thisField.find('.secondary_list').click(function(){

            var idCurrent = $(this).data('id');
            if($(this).is(':checked')){
              //add hidden field with this value
              var nameInput = thisField.find('.hidden_fields_secondary').data('name');
              var inputToAdd = $('<input type="hidden" class="secondary_hidden" name="'+nameInput+'[]" value="'+idCurrent+'">');

              thisField.find('.hidden_fields_secondary').append(inputToAdd);

            }else{
              //remove hidden field with this value
              thisField.find('input.secondary_hidden[value="'+idCurrent+'"]').remove();
            }
          });

      }
    </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
