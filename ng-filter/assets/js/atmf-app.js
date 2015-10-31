
 var Atmf = angular.module('atmf',['ngSanitize','ui.bootstrap','angularUtils.directives.dirPagination','angular-loading-bar']);


    Atmf.filter('html',function($sce){
        return function(input){
            return $sce.trustAsHtml(input);
        }
    });

  Atmf.directive("slider", function() {
    return {
        restrict: 'A',
        scope: {
            start: "=",
            end : "=",
            min : "=",
            max:  "=",
            key : "=",
            onchnage : "&",
            onend : "&",

        },

        link: function(scope, elem, attrs ) {

            jQuery(elem).slider({
                range: true,
                min: parseInt(scope.min),
                max: parseInt(scope.max),
				step: parseInt(attrs.step),
                value: [ scope.start , scope.end ],

                slide: function(event, ui) {
                    scope.$apply(function() {
                        scope.start = ui.value[0];
                        scope.end = ui.value[1];
                        scope.onchnage( scope.key , ui.value[0] , ui.value[1] );
                    });
                },
                stop: function( event, ui ) {
                    scope.$apply(function(){
                        scope.onend();
                    });
                }
            });
        }
    }
});

Atmf.directive("slider2", function() {
    return {
        restrict: 'A',
        scope: {
            start: "=",
            end : "=",
            min : "=",
            max:  "=",
            key : "=",
            onchnage : "&",
            onend : "&",

        },

        link: function(scope, elem, attrs ) {

            jQuery(elem).slider({
                range: true ,
                min: parseInt(attrs.min),
                max: parseInt(attrs.max),
				step: parseInt(attrs.step),
                values: [ scope.start , scope.end ],

                slide: function(event, ui) {
                    scope.$apply(function() {
                        scope.start = ui.values[0];
                        scope.end = ui.values[1];
                        scope.onchnage( scope.key , ui.values[0] , ui.values[1] );
                    });
                },
                stop: function( event, ui ) {
                    scope.$apply(function(){
                        scope.onend();
                    });
                }
            });
        }
    }
});




    Atmf.config(['cfpLoadingBarProvider', function( cfpLoadingBarProvider ) {
         cfpLoadingBarProvider.includeSpinner = false;
    }]);




  Atmf.controller('AtmfFrontEnd', ['$scope','$filter','$http', function( $scope , $filter , $http ){


   // var search_page =  JSON.parse(angular.element('#dso').text());







    if(  angular.isDefined(search_page) ){

        $scope.list =  search_page.metadata.list;

        $scope.seachPostType = search_page.metadata.search_post_type;

        $scope.postsPerPage = search_page.metadata.post_per_page;

        $scope.contentLimit = search_page.metadata.contentLimit;

        $scope.sortData = search_page.metadata.sort_meta;
    }




	$scope.formData = {};
	$scope.formMeta = {};






    $scope.addTometa = function(key, start , end){
        $scope.formMeta[key] = {
            start : start ,
            end  : end
        }
    }
	if( angular.isObject(search_page.post) ){

		$scope.posts = search_page.post;
	    $scope.totalItems = $scope.posts.length;

	}







    function throughItem(item , id ){


        //if( item.parent_taxonomy ){

            angular.forEach( item.alloption , function(option , key){

                if ( $scope.blankarray.indexOf( parseInt(key) ) == -1) {
                    $scope.blankarray.push( parseInt(key) );
                }

            });


        //}


        if( angular.isArray(item.items)){
            throughItem(item.items[0] , parseInt(id) );
        }







    }


	$scope.selected_taxonomy = [];


	$scope.grabResult = function(scope,model, ngitem){



		if( model == true && scope.key != undefined ) {

            if ( $scope.selected_taxonomy.indexOf( parseInt(scope.key) ) == -1) {
                $scope.selected_taxonomy.push( parseInt(scope.key) );
            }

            //  $scope.selected_taxonomy_refs.push(ngitem);
        }
            //else if( angular.isNumber(parseInt(model) ) ){
            //    //console.log(model);
            //
            //    if ( $scope.selected_taxonomy.indexOf( parseInt(model) ) == -1) {
            //        $scope.selected_taxonomy.push( parseInt(model) );
            //    }
            //
            //}

		else {

            $scope.blankarray = [];


            var taxonomy_name = [];

            //if(angular.isNumber(parseInt(model)) ) {
            //    scope.key = parseInt(model);
            //}

            // get all the options from the unselect checkbox and taxonomy name
            if( angular.isArray(ngitem.items) ){

                taxonomy_name.push(ngitem.taxonomy);
                throughItem( ngitem.items[0] , parseInt(scope.key) );

            }


           $scope.selected_taxonomy.splice( $scope.selected_taxonomy.indexOf(parseInt(scope.key)  ), 1 );






           angular.forEach( $scope.blankarray , function(blank_key) {

               // splicing all options from root unselect

               if(jQuery.inArray(blank_key ,$scope.selected_taxonomy) != -1){
                   $scope.selected_taxonomy.splice( $scope.selected_taxonomy.indexOf(parseInt(blank_key)  ), 1 );
               }

               // true / false change in the formdata
               angular.forEach($scope.formData , function(cat,taxonomy){

                    if( jQuery.inArray(taxonomy ,taxonomy_name) != -1 ){

                        angular.forEach(cat,function(category,category_key){

                            if( category_key == blank_key ){

                                cat[category_key] = false;
                            }
                        });
                    }
                });

           });



        } // else

        $scope.doFilter();

	}



$scope.grabMeta2 = function(key,min,max){
	$scope.formMeta[key] = [] ;
	$scope.formMeta[key]['start'] = 2000;
	$scope.formMeta[key]['end'] = 2100;
    $scope.doFilter();

    }

    $scope.grabMeta = function(){
       $scope.doFilter();
    }


   $scope.isObject = function(scope){

	   	if(angular.isObject(scope))
	   		return true;
	   	else
	   		return false;
   }


    $scope.filterData = {};



	 $scope.doFilter2 = function(key,min,max){


			$scope.formMeta[key]['start'] = min;
			$scope.formMeta[key]['end'] = max;
        	$scope.filterData.taxonomy = $scope.selected_taxonomy;
        	$scope.filterData.metadata = $scope.formMeta;
        	$scope.filterData.alltaxonomies = $scope.formData;
        	$scope.filterData.post_type = search_page.metadata.search_post_type;
            $scope.filterData.sort_meta = search_page.metadata.sort_meta;

            $scope.loading = true;

            $http({
                method: 'POST',
                url: atmf.ajaxurl+'?action=atmf_do_filter' ,
                data: jQuery.param({ 'filter': $scope.filterData }),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(function(e){
                $scope.posts = [];
                $scope.posts = e.data;

                $scope.totalItems = $scope.posts.length;
                $scope.loading = false;
            });

    }

    $scope.doFilter = function(){

        	$scope.filterData.taxonomy = $scope.selected_taxonomy;
        	$scope.filterData.metadata = $scope.formMeta;
        	$scope.filterData.alltaxonomies = $scope.formData;
        	$scope.filterData.post_type = search_page.metadata.search_post_type;
            $scope.filterData.sort_meta = search_page.metadata.sort_meta;

            $scope.loading = true;
            $http({
                method: 'POST',
                url: atmf.ajaxurl+'?action=atmf_do_filter' ,
                data: jQuery.param({ 'filter': $scope.filterData }),
                headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
            }).then(function(e){
                $scope.posts = [];
                $scope.posts = e.data;

                $scope.totalItems = $scope.posts.length;
                $scope.loading = false;
            });
            filters = [];
            filters_key = [];
            for (filter_key in $scope.filterData.alltaxonomies) {
                for (filter_value in $scope.filterData.alltaxonomies[filter_key]) {
                    if (filter_value == '$$hashKey') {
                        continue;
                    }
                    if ($scope.filterData.alltaxonomies[filter_key][filter_value]) {
                        filters.push($('.filter-key-'+filter_value+' > label span').text().trim());
                        filters_key.push(filter_value);
                    }
                }
            }

            for (filter_key in $scope.filterData.metadata) {
                filter_values = [];
                for (filter_value in $scope.filterData.metadata[filter_key]) {
                    add_to_filters = $scope.filterData.metadata[filter_key][filter_value];
                    filter_value = toTitleCase(filter_value.replace('-',' '));
                    if ($('.filter-key-'+filter_value).length) {
                        filter_value = $('.filter-key-'+filter_value+' > label span').text();
                    }
                    if (add_to_filters) {
                        filter_values.push(filter_value);
                    }
                }
                filters.push(filter_values.join(', '));
                filters_key.push(filter_key);
            }

            $('.filters-reset ul').html('');
            for (key in filters) {
                if (filters[key].length) {
                    $('.filters-reset ul').append('<li>'+filters[key]+' <a href="#" class="remove" data-key="'+filters_key[key]+'">x</a></li>');
                }
            }
            if ($('.filters-reset ul li').length) {
                $('.filters-reset').slideDown('fast');
            } else {
                $('.filters-reset').slideUp('fast');
            }

            // remove filters
            $('.filters-reset .remove').on('click', function() {
                to_remove = $(this).data('key');
                if ($('.filter-key-'+to_remove).length) {
                    $('.filter-key-'+to_remove+' > input[type="checkbox"]').click();
                } else if ($('.filter-set-'+to_remove).length) {
                    $('.filter-set-'+to_remove+' input[type="checkbox"]:checked').each(function() {
                        $(this).click();
                    });
                }
                $scope.doFilter();
            });
            $('#filter_locations').val('').trigger('keyup');
    }

    $scope.doReset = function(){
            location.reload();
    }

    $scope.postView = 'list';

  }]);


function toTitleCase(str) {
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}
function changeHTMLContent() {

    // sorting
    $('.sort-container option').each(function() {
        $(this).html(toTitleCase($(this).html().replace('_',' ')));
    });

    // sidebar filters
    $('.filter-list-item input + label > span').each(function() {
        if ($(this).html() == '---') {
            $(this).parent().remove();
        } else {
            $(this).html(toTitleCase($(this).html().replace('-',' ')));
        }
    });
    $('.filter-title-8').html('Localizare');
}
function moveLocations() {
    var subBucuresti = $("<div class='content-child'></div>");
    subBucuresti.append($('.filter-id-9'));
    subBucuresti.append($('.filter-id-11'));
    $('.filter-key-51').append(subBucuresti).addClass('filter-parent');
}

function filterLocations() {
    $('.filter-content-8').prepend('<input type="text" placeholder="Caută" id="filter_locations">');
    $('#filter_locations').on('keyup', function() {
        search_for = $(this).val().toUpperCase();
        if (search_for.length) {
            $('.content-child').show();
            $('.content-child').prev('label').addClass('opened');
            $(this).parent().find('label span').each(function() {
                if ($(this).html().toUpperCase().indexOf(search_for) == -1) {
                    if (!$(this).parent().parent().hasClass('filter-parent'))
                        $(this).parent().parent().hide();
                } else {
                    $(this).parent().parent().show();
                }
            });
        } else {
            $(this).parent().find('li').show();
        }
    });
}

angular.element(document).ready(function () {
    changeHTMLContent();
    moveLocations();
    filterLocations();

    // reset filters
    $('.filters-reset-link').on('click', function(e) {
        e.preventDefault();
        $('.filters-reset .remove').each(function() {
            $('.filters-reset .remove').click();
        });
    });

    // open/close child elements
    $('.filter-parent label').on('click', function() {
        $(this).next('.content-child').slideToggle('fast');
        $(this).toggleClass('opened');
    });
});