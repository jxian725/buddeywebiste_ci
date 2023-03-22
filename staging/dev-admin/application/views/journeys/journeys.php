<?php
defined('BASEPATH') OR exit('No direct script access allowed');
global $permission_arr;
?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box box-primary">
            <div class="box-body">
                <?php if( in_array( 'journey/add', $permission_arr ) ) { ?>
                <span onClick="return journeyForm();" class="btn btn-success btn-xs pull-right">
                    <i class="fa fa-plus"></i>&nbsp; Add
                </span>
                <?php } ?>
                <section id="no-more-tables">
                    <table class="table table-bordered table-striped table-condensed cf">
                        <thead class="cf">
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Description</th> 
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="journeylist">
                            <?php 
                            if( $journeyList ) {
                                foreach ( $journeyList as $key => $value ) {
                                    if( $value->image ) {
                                        $path = "http://" . $_SERVER['SERVER_NAME'] . '/ebuddey-admin/uploads/journey/' . $value->image;
				                        //file_exists( $path );
                                        if( $value->image ) {
                                            $image = "http://" . $_SERVER['SERVER_NAME'] . '/ebuddey-admin/uploads/journey/' . $value->image;
                                        } else {
                                            $image = $this->config->item( 'dir_url' ).'uploads/no_image.png';
                                        }
                                    } else {
                                        $image = $this->config->item( 'dir_url' ).'uploads/no_image.png';
                                    }
                                    echo '<tr>
                                            <td>
                                                <img style="width: 80px;" class="media-object" src="' . $image . '" />
                                            </td>
                                            <td>
                                                '. $value->name .'
                                        </td>
                                        <td>' . rawurldecode( $value->description ) . '</td>
                                        <td>';
                                        if( in_array( 'journey/add', $permission_arr ) ) {
                                          echo '<a href="javascript:;" onClick="return editjourney( ' . $value->journey_id . ' );" class="btn btn-warning btn-xs">Edit</a>&nbsp;';
                                        }
                                        if( in_array( 'journey/delete', $permission_arr ) ) {
                                            echo '<span onClick="return deleteJourney( ' . $value->journey_id . ' );" class="btn btn-danger btn-xs">Delete</span>';
                                        }
                                    echo '</td>
                                         </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="4">No data to display.</td></tr>';
                            }
                        ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    </div>        
</div>
<script type="text/javascript">
    function journeyForm(){
        $.ajax( {
            type: "POST",
            url: adminurl + 'journeys/journeyForm',
            success: function( msg ) {
              $( '#myModal .modal-title' ).html( 'Add Journey' );
              $( '#myModal .modal-body' ).html( msg );
              $( '#myModal .modal-footer' ).html( '' );
              $( '#myModal' ).modal( 'show' );
            }
        });
        return false;
    }
    function addJourney( type ) {

        var error   = 0;
        if( type == 'edit' ) {
            var $btn    = $( '#update-journey' );
            $btn.button( 'loading' );

            var image   = $( '#edit-journey-form #a_image' ).val();
            var link    = $( '#edit-journey-form #a_link' ).val();
            var desc    = $( '#edit-journey-form #a_desc' ).val();
        } else {
            var $btn    = $( '#create-journey' );
            $btn.button( 'loading' );
            
            var image   = $( '#journey-form #a_image' ).val();
            var link    = $( '#journey-form #a_link' ).val();
            var desc    = $( '#journey-form #a_desc' ).val();
        }
        

        if( link == '' ) {
            toastr.error( 'Please enter the name', 'Error' );
            error = 1; 
        }

        /*if( type != 'edit' ) {

            switch(image.substring(image.lastIndexOf('.') + 1).toLowerCase()){
                case 'gif': case 'jpg': case 'png':
                    break;
                default:
                    //$( '#advertisement-form #a_image' ).image('');
                    // error message here
                    toastr.error( 'Please select image.', 'Error' );
                    error = 1;
                    break;
            }

        } */ 

        if( desc == '' ) {
            toastr.error( 'Please enter the description', 'Error' );
            error = 1; 
        }  

        if( error == 0 ) {
            if( type == 'edit' ) {
                $( '#edit-journey-form' ).submit();
            } else {
                $( '#journey-form' ).submit();
            } 
            
            return true;
        }
        $btn.button( 'reset' );
        return false;
    }
    function editjourney( journeyID ) {

        var data = 'journeyID=' + journeyID;
        $.ajax( {
            type: "POST",
            url: adminurl + 'journeys/editJourneyForm',
            data    : data,
            success: function( msg ) {
              $( '#myModal .modal-title' ).html( 'Edit journey' );
              $( '#myModal .modal-body' ).html( msg );
              $( '#myModal .modal-footer' ).html( '' );
              $( '#myModal' ).modal( 'show' );
            }
        });
        return false;
    }
    function deleteJourney( journeyID ) {
        $( '#myModal .modal-title' ).html( 'Confirm' );
        $( '#myModal .modal-body' ).html( 'Are you sure want to delete the journey ?' );
        $( '#myModal .modal-body' ).append( '<br /><br /><button class="btn btn-info btn-sm" id="continuemodal" data-dismiss="modal">Yes</button>&nbsp;&nbsp;<button aria-hidden="true" data-dismiss="modal" class="btn btn-danger btn-sm">Cancel</button>' );
        $( '#myModal' ).modal({ backdrop: 'static', keyboard: false }); 
        $( "#continuemodal" ).click(function() {
            var account_data    = 'journeyID=' + journeyID;
            $.ajax({
                type: "POST",
                url: adminurl + 'journeys/deleteJourney',
                data: account_data,
                success: function( data ) {
                  toastr.success( 'Journey deleted successfully.','Success' );
                  setTimeout( function() {
                    window.location.href = adminurl + 'journeys';
                  }, 1000 );
                }
            });
        });    
      return false;
    }
</script>