<?php 
$promovat = get_post_meta($post->ID, 'anunt_promovat', true); 

//$promovat = get_field('anunt_promovat'); 

?>

        <div class="row control-area">
            <!-- <ul class="view-toggle pull-left">
                <li><a href="#" ng-click="postView='list'" data-layout="with-thumb" class="btn glyphicon glyphicon-th-list"></a></li>
                <li><a href="#" ng-click="postView='grid'" data-layout="" class="btn glyphicon glyphicon-th"></a></li>
            </ul> -->
            <!-- <div class="col-md-4 pull-right" >
				<select class="form-control"  ng-model="postsPerPage">
					<option value="10">10</option>
					<option value="30">30</option>
					<option value="40">60</option>
				</select>
            </div> -->
            <div class="col-md-5 col-md-offset-1">
                <div class="row">
                    <div class="col-md-12 sort-container">
                        <a href="#" ng-click="postOrder.order = false " class="btn btn-xs btn-praimary pull-right">
                            <span class="glyphicon glyphicon-arrow-down"></span>
                        </a>
                        <select class="form-control pull-left sort-dropdown" ng-init="postOrder.order = 'reverse'" ng-model="postOrder.type"  >
                            <option value="">Sortare</option>
                            <option ng-repeat="(key ,value) in sortData" value="{{value.label}}">{{value.text}}</option>
                        </select>
                          <a href="#" ng-click="postOrder.order = 'reverse' " class="btn btn-xs btn-praimary pull-right">
                            <span class="glyphicon glyphicon-arrow-up"></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6"></div>
        </div>



    	<div class="clearfix"></div>

		<div dir-paginate="post in posts | itemsPerPage: postsPerPage | filter: QuickSearch | orderBy:postOrder.type:postOrder.order">
			<div ng-if="postView == 'list' " class="row post-item <?php if($promovat) echo 'promovat'; ?>">
				<div class="col-md-4"><img class="thumbnail" ng-src="{{ post.post_thumbnail }}"/></div>
				<a style="font-size: 18px;" href="{{ post.post_permalink }}" ng-bind="post.post_title"></a>
				<p ng-bind-html="post.post_content | limitTo: contentLimit | html"></p>

				<p>{{ post.bucuresti }}</p>
				<p>{{ post.etaj }}</p>
			</div>

			<div ng-if="postView == 'grid'" class="col col-md-4 post-item-grid">
				<img class="thumbnail" ng-src="{{ post.post_thumbnail }}"/>
				<a style="font-size: 15px;" href="{{ post.post_permalink }}" ng-bind="post.post_title"></a>
			</div>
		</div>

		<div class="loading" ng-show="loading"><!-- <i></i><i></i><i></i> --></div>
		<div class="no-results" type="danger" ng-show="( posts | filter:QuickSearch).length==0">
            Nici un rezultat gasit. <a href="#" class="filters-reset-link">Reseteaza filtrele de cautare</a>.
		</div>

		<div class="clearfix"></div>

		<!-- Start pagination  -->
		<dir-pagination-controls boundary-links="true" class="pull-right" on-page-change="pageChangeHandler(newPageNumber)" template-url="<?php  echo UOU_ATMF_URL .'/assets/js/vendor/angular-utils-pagination/dirPagination.tpl.html';  ?>"></dir-pagination-controls>
		<!-- End pagination  -->