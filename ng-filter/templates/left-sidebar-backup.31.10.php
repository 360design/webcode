

                <input type="text" placeholder="<?php the_title(); ?>" class="form-control filter-text" ng-model="QuickSearch">

                <div class="filters-container">

                    <div class="filters-reset">
                        <a href="#" class="filters-reset-link">Reseteaza filtre de cautare</a>
                        <ul></ul>
                    </div>

                    <script type="text/ng-template" id="items_renderer.html"> 


                        <!-- for taxonomy  -->
                        <div ng-if="item.option == 'taxonomy' && !item.parent_taxonomy" class="filter-id-{{item.id}}">

                                <a class="filter-title filter-title-{{item.id}}">{{ item.title }}</a>

                                <div ng-if="item.taxonomy" class="filter-content filter-content-{{item.id}}">

                                    <div ng-show="item.viewType == 'checkbox'">
                                        <ul>
                                            <li ng-repeat="(key, value) in item.alloption" class="filter-key-{{key}}" data-key="{{key}}">
                                                <input type="checkbox" ng-change="grabResult( this ,formData[item.taxonomy][key], item)"  name="{{value}}" ng-model="formData[item.taxonomy][key]" id="checkbox-{{key}}">
                                                <label for="checkbox-{{key}}">
                                                    <span>{{value}}</span>
                                                </label>
                                            </li>
                                        </ul>
                                    </div>

                                    <div ng-show="item.viewType == 'select'">
                                        <select  chosen class="form-control" ng-change="grabResult( this ,formData[item.taxonomy] , item)" ng-model="formData[item.taxonomy]">
                                            <option value="">Please select</option>
                                            <option value="{{key}}" ng-repeat="(key ,value) in item.alloption">{{value}}</option>
                                        </select>
                                    </div>

                                </div>

                        </div>

                        <!-- for metadata   -->

                        <div ng-if="item.option == 'metadata' ">
                            <a class="filter-title filter-title-{{item.metakey}}">{{ item.title }}</a>

                            <div ng-if="item.viewType =='range' ">
                                     <div ng-if="item.title =='Pret inchiriere' " class="range-slider clearfix" style="width:200px">
                                        <div slider style="width:180px" min="item.rangeStart"  max="item.rangeEnd"  start="item.start" end="100" step="10" class="cdbl-slider" onend="grabMeta()" onchnage="addTometa(item.metakey ,item.start , item.end)" key="item.metakey" ng-model="formData[item.metakey]" ></div>
                                        <br/>
                                        <span> {{item.start}} </span>
                                         <span style="float:right;"> {{item.end}} </span>
                                     </div>
                                    <div ng-if="item.title =='Pret vanzare' " class="range-slider clearfix" style="width:200px">
                                        <div slider style="width:180px" min="item.rangeStart"  max="item.rangeEnd" start="item.start" end="item.end" step="100" class="cdbl-slider" onend="grabMeta()" onchnage="addTometa(item.metakey ,item.start , item.end)" key="item.metakey" ></div>
                                        <br/>
                                        <span> {{item.start}} </span>
                                         <span style="float:right;"> {{item.end}} </span>
                                    </div>
                                    <div ng-if="item.title =='Suprafata utila' " class="range-slider clearfix" style="width:200px">
                                        <div slider style="width:180px" min="item.rangeStart"  max="item.rangeEnd" start="item.start" end="item.end" step="1" class="cdbl-slider" onend="grabMeta()" onchnage="addTometa(item.metakey ,item.start , item.end)" key="item.metakey" ></div>
                                        <br/>
                                        <span> {{item.start}} </span>
                                         <span style="float:right;"> {{item.end}} </span>
                                    </div>
                                    <div ng-if="item.title =='Etaj' " class="range-slider clearfix" style="width:200px">
                                        <div slider style="width:180px" min="item.rangeStart"  max="item.rangeEnd" start="item.start" end="item.end" step="1" class="cdbl-slider" onend="grabMeta()" onchnage="addTometa(item.metakey ,item.start , item.end)" key="item.metakey" ></div>
                                        <br/>
                                        <span> {{item.start}} </span>
                                         <span style="float:right;"> {{item.end}} </span>
                                    </div>
                            </div>

                            <div ng-switch on="item.title">
                                <div ng-switch-when='Ansamblu rezidential' >
                                    <div ng-show="item.viewType == 'checkbox' ">
                                        <ul ng-if="item.metakey">
                                            <li ng-repeat="(key, value) in item.alloption" data-key="{{key}}">
                                              <input type="checkbox" ng-change="grabMeta()" name="{{key}}" ng-model="formMeta[item.metakey][key]" id="checkbox-{{key}}">
                                                <label for="checkbox-{{key}}">
                                                    <span>{{value}}</span>
                                                </label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            <div ng-switch-when='An constructie' >
                            <div ng-show="item.viewType == 'checkbox' " class="filter-set-{{item.metakey}}">
                                <ul ng-if="item.metakey">
                                    <li class="filter-key-1977">
                                        <input type="checkbox" ng-change="grabMeta()" name="1977" ng-model="formMeta[item.metakey][1977]" id="checkbox-1977">
                                        <label for="checkbox-1977">
                                            <span>inainte de 1977</span>
                                        </label>
                                    </li>
                                    <li class="filter-key-2000">
                                        <input  type="checkbox" ng-change="grabMeta()" name="2000" ng-model="formMeta[item.metakey][2000]" id="checkbox-2000">
                                        <label for="checkbox-2000">
                                            <span>1977 - 2000</span>
                                        </label>
                                    </li>
                                    <li class="filter-key-2015">
                                        <input  type="checkbox" ng-change="grabMeta()" name="2015" ng-model="formMeta[item.metakey][2015]" id="checkbox-2015">
                                        <label for="checkbox-2015">
                                            <span>2000 - prezent</span>
                                        </label>
                                    </li>

                                </ul>
                            </div>
                            </div>
                            <div ng-switch-default>
                            <div ng-show="item.viewType == 'checkbox' ">
                                <ul ng-if="item.metakey" class="filter-set-{{item.metakey}}">
                                    <li ng-repeat="(key, value) in item.alloption" class="filter-list-item">
                                       <input type="checkbox" ng-change="grabMeta()" name="{{value}}" ng-model="formMeta[item.metakey][value]" id="checkbox-{{value}}">
                                        <label for="checkbox-{{value}}">
                                            <span>{{value}}</span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            </div>
                            </div>

                            <div ng-show="item.viewType == 'bool' ">
                                <ul ng-if="item.metakey">
                                    <li>
                                      <input type="checkbox" ng-change="grabMeta()" name="{{value}}" ng-model="formMeta[item.metakey][1]" id="checkbox-{{item.metakey}}">
                                        <label for="checkbox-{{item.metakey}}">
                                            <span>{{value}}</span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            <div ng-show="item.viewType == 'radio' ">
                                <ul ng-if="item.metakey">
                                    <li ng-repeat="(key, value) in item.alloption">
                                      <input type="radio" name="{{item.metakey}}" ng-model="formMeta[item.metakey]" ng-value="{{value}}" ng-change="grabMeta()"> {{value}}
                                    </li>
                                </ul>
                            </div>
                            <div ng-show="item.viewType == 'select' ">
                                    <select class="form-control" ng-change="grabMeta()" ng-model="formMeta[item.metakey]" ng-options="o as o for o in item.alloption"></select>
                             </div>

                        </div>


                        <!--  second stage  , it will show after its parent show      -->
                        <div ng-show="selected_taxonomy.indexOf(item.parent_taxonomy)!=-1">
                            <a class="filter-title filter-title-{{item.metakey}}">{{ item.title }}</a>

                            <div ng-if="item.taxonomy" class="filter-content filter-content-{{item.metakey}}">

                                <div ng-show="item.viewType == 'checkbox'">
                                    <ul>
                                        <li ng-repeat="(key, value) in item.alloption">
                                            <input type="checkbox" ng-change="grabResult( this ,formData[item.taxonomy][key], item)"  name="{{value}}" ng-model="formData[item.taxonomy][key]" id="checkbox-{{key}}">
                                            <label for="checkbox-{{key}}">
                                                <span>{{value}}</span>
                                            </label>
                                        </li>
                                    </ul>
                                </div>

<!--                                <div ng-show="item.viewType == 'select'">-->
<!--                                    <select class="form-control" ng-change="grabResult( this ,formData[item.taxonomy] , item)" ng-model="formData[item.taxonomy]">-->
<!--                                        <option value="">Please select</option>-->
<!--                                        <option value="{{key}}" ng-repeat="(key ,value) in item.alloption">{{value}}</option>-->
<!--                                    </select>-->
<!--                                </div>-->

                            </div>

                        </div>
                        <div ng-repeat="item in item.items" ng-include="'items_renderer.html'"></div>




                    </script>

                    <div ng-repeat="item in list" ng-include="'items_renderer.html'"></div>

                    <div class="clearfix"></div>

                    <!-- <a ng-click="doFilter()" class="btn btn-primary filter-btn" href="#"> <?php  _e('Filtrare','atmf');  ?></a>
                    <a ng-click="doReset()" class="btn btn-primary filter-btn" href="#"> <?php  _e('Resetare','atmf');  ?></a> -->

                </div>