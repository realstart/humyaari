<template>
   <div>
      <vue-progress-bar></vue-progress-bar>
      <!-- <div v-if="!success" class="row">
         <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
               <span class="info-box-icon bg-red">
               <span class="fa fa-shopping-cart"></span>
               </span>
               <div class="info-box-content">
                  <span class="info-box-text">Sales</span>
                  <span class="info-box-number" v-if="TotalSale <= 0 "> 0 BDT</span>  <span class="info-box-number" v-else>  {{ this.TotalSale }} BDT </span>
               </div>
            </div>
         </div>
         <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
               <span class="info-box-icon bg-yellow">
               <span class="fa fa-clock-o"></span>
               </span>
               <div class="info-box-content">
                  <span class="info-box-text">Due</span>
                  <span class="info-box-number" v-if="TotalDue <= 0">0 BDT </span> <span class="info-box-number" v-else> {{ TotalDue }} BDT</span>
               </div>
            </div>
         </div>
         <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
               <span class="info-box-icon bg-aqua">
               <span class="fa fa-arrow-up"></span>
               </span>
               <div class="info-box-content">
                  <span class="info-box-text">Income</span>
                  <span class="info-box-number" v-if="TotalIncomes <= 0 "> 0 BDT</span>  <span class="info-box-number" v-else>  {{ this.TotalIncomes}} BDT </span>
               </div>
            </div>
          
         </div>
         
         <div class="clearfix visible-sm-block"></div>
         <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
               <span class="info-box-icon bg-green">
               <span class="fa fa-arrow-down"></span>
               </span>
               <div class="info-box-content">
                  <span class="info-box-text">Expense</span>
                  <span class="info-box-number" v-if="TotalExpenses <= 0 "> 0 BDT</span>  <span class="info-box-number" v-else>  {{ this.TotalExpenses}} BDT </span>
               </div>
            </div>
         </div>
         </div>
         <div  v-if="!success" class="row" style="margin:5px 0px">
         <div class="col-md-12" style="background-color:white">
            <div class="col-md-8">
               <h3 class="text-left report_header box-header with-border">FNo</h3>
               <div class="sales-graph">
                  <canvas id="sales-graph"></canvas>
               </div>
            </div>
            <div class="col-md-4 col-sm-12">
               <h3 class="text-left report_header box-header with-border">Statistics</h3>
               <table class="table table-bordered statistics_table">
                  <tr>
                     <td> <b>Deposit Amount</b></td>
                     <td v-if="TotalSevenDaysDeposit <= 0"> 0 BDT</td>
                     <td v-else> {{TotalSevenDaysDeposit}} BDT</td>
                  </tr>
                  <tr>
                     <td> <b>Withdraw Amount</b></td>
                     <td v-if="TotalSevenDaysWithdraw <= 0">0 BDT</td>
                     <td v-else> {{TotalSevenDaysWithdraw}} BDT</td>
                  </tr>
                  <tr>
                     <td> <b>Transfer Amount</b></td>
                     <td v-if="TotalSevenDaysTransfer <= 0"> 0 BDT</td>
                     <td v-else> {{TotalSevenDaysTransfer}} BDT</td>
                  </tr>
               </table>
               <h3 class="text-left report_header box-header with-border">User Log</h3>
               <ul class="list-group" v-show="userLog.length" v-for="(user, index) in userLog" :key="user.id">
                  <li style="list-style: none;margin-bottom: -12px;"> {{index + 1}}. {{ user.name }} ( {{ user.updated_at | moment("MMMM Do YYYY, h:mm: a") }} ) <i v-if="curuser.id == user.id" class="fa fa-user"  style="color:green;float: right;" aria-hidden="true"></i> <i v-if="curuser.id != user.id" class="fa fa-user"  style="color:red;float: right;" aria-hidden="true"></i></li>
               </ul>
            </div>
         </div>
         </div> -->
      <section class="wrapper" id="report-page-wrapper">
         <!--mini statistics start-->
         <div class="row">
            <!-- <div class="col-md-3">
               <section class="panel">
                  <div class="panel-body">
                     <div class="top-stats-panel">
                        <h4 class="widget-h">Monthly Expense</h4>
                        <div class="sm-pie">
                          <vue-svg-gauge
                            :start-angle="-100"
                            :end-angle="100"
                            :value="7"
                            :separator-step="0"
                            :min="0"
                            :max="40"
                            gauge-color="#fa8564"
                            :scale-interval="0.6"
                            />
                        </div>
                     </div>
                  </div>
               </section>
            </div>
            <div class="col-md-3">
               <section class="panel">
                  <div class="panel-body">
                     <div class="top-stats-panel">
                        <div class="daily-visit">
                           <h4 class="widget-h">Daily Expense</h4>
                           <div id="daily-visit-chart" style="width:100%; height: 100px; display: block">
                                <canvas id="sales-graph"></canvas>
                           </div>   
                        </div>
                     </div>
                  </div>
               </section>
            </div> -->
            <div class="col-md-6">
               <section class="panel">
                  <div class="panel-body">
                     <div class="top-stats-panel">
                        <h4 class="widget-h">Monthly Sale</h4>
                        <div class="sm-pie">
                           <vc-donut
                              background="white" foreground="grey"
                              :size="100" unit="px" :thickness="30"
                              has-legend legend-placement="right"
                              :sections="sections" :total="100"
                              @section-click="handleSectionClick"
                              ></vc-donut>
                        </div>
                     </div>
                  </div>
               </section>
            </div>
            <div class="col-md-6">
               <section class="panel">
                  <div class="panel-body">
                     <div class="top-stats-panel">
                        <h4 class="widget-h">Daily Sales</h4>
                        <div class="bar-stats">
                           <ul class="progress-stat-bar clearfix">
                              <li data-percent=""><span class="progress-stat-percent pink" :style="'height:' + PercentageGDS + '%'"></span></li>
                              <li data-percent=""><span class="progress-stat-percent" :style="'height:' + PercentageService + '%'"></span></li>
                              <li data-percent=""><span class="progress-stat-percent yellow-b" :style="'height:' + PercentageThirdParty + '%'"></span></li>
                           </ul>
                           <ul class="bar-legend">
                              <li><span class="bar-legend-pointer pink"></span> GDS</li>
                              <li><span class="bar-legend-pointer green"></span> Services</li>
                              <li><span class="bar-legend-pointer yellow-b"></span> Third Party</li>
                           </ul>
                           <div class="daily-sales-info">
                              <span class="sales-count" v-if="totalSalesAll <= 0">0</span> <span v-else> {{ totalSalesAll }}</span> </span> <span class="sales-label">Daily sales</span>
                           </div>
                        </div>
                     </div>
                  </div>
               </section>
            </div>
         </div>
         <!--mini statistics end-->
         <!--     <div class="row">
            <div class="col-md-8">
                 <section class="panel">
                     <header class="panel-heading">
                         Earning Graph <span class="tools pull-right">
                     <a href="javascript:;" class="fa fa-chevron-down"></a>
                     <a href="javascript:;" class="fa fa-cog"></a>
                     <a href="javascript:;" class="fa fa-times"></a>
                     </span>
                     </header>
                     <div class="panel-body">
            
                         <div id="graph-area" class="main-chart">
                         </div>
                         <div class="region-stats">
                             <div class="row">
                                 <div class="col-md-7">
                                     <div class="region-earning-stats">
                                         This year total earning <span>$68,4545,454</span>
                                     </div>
                                     <ul class="clearfix location-earning-stats">
                                         <li class="stat-divider">
                                             <span class="first-city">$734503</span>
                                             Rocky Mt,NC </li>
                                         <li class="stat-divider">
                                             <span class="second-city">$734503</span>
                                             Dallas/FW,TX </li>
                                         <li>
                                             <span class="third-city">$734503</span>
                                             Millville,NJ </li>
                                     </ul>
                                 </div>
                                 <div class="col-md-5">
                                     <div id="world-map" class="vector-stat">
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </section>
             </div> 
             <div class="col-md-4">
                
                 <section class="panel">
                     <div class="panel-body">
                         <div class="monthly-stats pink">
                             <div class="clearfix">
                                 <h4 class="pull-left"> January 2013</h4>
                                
                                 <div class="btn-group pull-right stat-tab">
                                     <a href="#line-chart" class="btn stat-btn active" data-toggle="tab"><i class="ico-stats"></i></a>
                                     <a href="#bar-chart" class="btn stat-btn" data-toggle="tab"><i class="ico-bars"></i></a>
                                 </div>
                             </div>
                             
                             <div class="tab-content">
                                 <div class="tab-pane active" id="line-chart">
                                     <div class="sparkline" data-type="line" data-resize="true" data-height="75" data-width="90%" data-line-width="1" data-min-spot-color="false" data-max-spot-color="false" data-line-color="#ffffff" data-spot-color="#ffffff" data-fill-color="" data-highlight-line-color="#ffffff" data-highlight-spot-color="#e1b8ff" data-spot-radius="3" data-data="[100,200,459,234,600,800,345,987,675,457,765]">
                                     </div>
                                 </div>
                                 <div class="tab-pane" id="bar-chart">
                                     <div class="sparkline" data-type="bar" data-resize="true" data-height="75" data-width="90%" data-bar-color="#d4a7f5" data-bar-width="10" data-data="[300,200,500,700,654,987,457,300,876,454,788,300,200,500,700,654,987,457,300,876,454,788]"></div>
                                 </div>
                             </div>
                         </div>
                         <div class="circle-sat">
                             <ul>
                                 <li class="left-stat-label"><span class="sell-percent">60%</span><span>Direct Sell</span></li>
                                 <li><span class="epie-chart" data-percent="45">
                                 <span class="percent"></span>
                                 </span></li>
                                 <li class="right-stat-label"><span class="sell-percent">40%</span><span>Channel Sell</span></li>
                             </ul>
                         </div>
                     </div>
                 </section>
                 <section class="panel">
                     <div class="panel-body">
                         <ul class="clearfix prospective-spark-bar">
                             <li class="pull-left spark-bar-label">
                                 <span class="bar-label-value"> $18887</span>
                                 <span>Prospective Label</span>
                             </li>
                             <li class="pull-right">
                                 <div class="sparkline" data-type="bar" data-resize="true" data-height="40" data-width="90%" data-bar-color="#f6b0ae" data-bar-width="5" data-data="[300,200,500,700,654,987,457,300,876,454,788,300,200,500,700,654,987,457,300,876,454,788]"></div>
                             </li>
                         </ul>
                     </div>
                 </section>
                 <section class="weather-widget clearfix">
                     <div class="pull-left weather-icon">
                         <canvas id="icon1" width="60" height="60"></canvas>
                     </div>
                     <div>
                         <ul class="weather-info">
                             <li class="weather-city">New York <i class="ico-location"></i></li>
                             <li class="weather-cent"><span>18</span></li>
                             <li class="weather-status">Rainy Day</li>
                         </ul>
                     </div>
                 </section>
             </div>
            </div>-->
         <!--mini statistics start-->    
         <div class="row">
            <div class="col-md-3">
               <div class="mini-stat clearfix">
                  <span class="mini-stat-icon orange"><i class="fa fa-gavel"></i></span>
                  <div class="mini-stat-info">
                     <span v-if="totalTicket <= 0">0</span>
                     <span v-else>{{ totalTicket}}</span>
                     Total Ticket Sale
                  </div>
               </div>
            </div>
            <div class="col-md-3">
               <div class="mini-stat clearfix">
                  <span class="mini-stat-icon tar"><i class="fa fa-tag"></i></span>
                  <div class="mini-stat-info">
                     <span v-if="totalService <= 0">0</span>
                     <span v-else>{{ totalService}}</span>
                     Total Services Sold
                  </div>
               </div>
            </div>
            <div class="col-md-3">
               <div class="mini-stat clearfix">
                  <span class="mini-stat-icon pink"><i class="fa fa-money"></i></span>
                  <div class="mini-stat-info">
                     <span v-if="totalProfit <= 0">0</span>
                     <span v-else>{{ Number((totalProfit).toFixed(2)) }}</span>
                     Total Profit
                  </div>
               </div>
            </div>
            <div class="col-md-3">
               <div class="mini-stat clearfix">
                  <span class="mini-stat-icon green"><i class="fa fa-eye"></i></span>
                  <div class="mini-stat-info">
                     <span v-if="totalCustomer <= 0">0</span>
                     <span v-else>{{ totalCustomer}}</span>
                     Total Customers
                  </div>
               </div>
            </div>
         </div>
         <!--mini statistics end-->
         <!-- <div class="row">
            <div class="col-md-8">
                <div class="event-calendar clearfix">
                    <div class="col-lg-7 calendar-block">
                        <div class="cal1 "><div class="clndr"><div class="clndr-controls"><div class="clndr-control-button"><span class="clndr-previous-button"><i class="fa fa-chevron-left"></i></span></div><div class="month">August 2019</div><div class="clndr-control-button leftalign"><span class="clndr-next-button"><i class="fa fa-chevron-right"></i></span></div></div><table class="clndr-table" border="0" cellspacing="0" cellpadding="0"><thead><tr class="header-days"><td class="header-day">S</td><td class="header-day">M</td><td class="header-day">T</td><td class="header-day">W</td><td class="header-day">T</td><td class="header-day">F</td><td class="header-day">S</td></tr></thead><tbody><tr><td class="day past adjacent-month last-month calendar-day-2019-07-28"><div class="day-contents">28</div></td><td class="day past adjacent-month last-month calendar-day-2019-07-29"><div class="day-contents">29</div></td><td class="day past adjacent-month last-month calendar-day-2019-07-30"><div class="day-contents">30</div></td><td class="day past adjacent-month last-month calendar-day-2019-07-31"><div class="day-contents">31</div></td><td class="day past calendar-day-2019-08-01"><div class="day-contents">1</div></td><td class="day past calendar-day-2019-08-02"><div class="day-contents">2</div></td><td class="day past calendar-day-2019-08-03"><div class="day-contents">3</div></td></tr><tr><td class="day past calendar-day-2019-08-04"><div class="day-contents">4</div></td><td class="day past calendar-day-2019-08-05"><div class="day-contents">5</div></td><td class="day past calendar-day-2019-08-06"><div class="day-contents">6</div></td><td class="day past calendar-day-2019-08-07"><div class="day-contents">7</div></td><td class="day past calendar-day-2019-08-08"><div class="day-contents">8</div></td><td class="day past calendar-day-2019-08-09"><div class="day-contents">9</div></td><td class="day past event calendar-day-2019-08-10"><div class="day-contents">10</div></td></tr><tr><td class="day past event calendar-day-2019-08-11"><div class="day-contents">11</div></td><td class="day past event calendar-day-2019-08-12"><div class="day-contents">12</div></td><td class="day past event calendar-day-2019-08-13"><div class="day-contents">13</div></td><td class="day past event calendar-day-2019-08-14"><div class="day-contents">14</div></td><td class="day past calendar-day-2019-08-15"><div class="day-contents">15</div></td><td class="day past calendar-day-2019-08-16"><div class="day-contents">16</div></td><td class="day past calendar-day-2019-08-17"><div class="day-contents">17</div></td></tr><tr><td class="day past calendar-day-2019-08-18"><div class="day-contents">18</div></td><td class="day past calendar-day-2019-08-19"><div class="day-contents">19</div></td><td class="day past calendar-day-2019-08-20"><div class="day-contents">20</div></td><td class="day past event calendar-day-2019-08-21"><div class="day-contents">21</div></td><td class="day past event calendar-day-2019-08-22"><div class="day-contents">22</div></td><td class="day past event calendar-day-2019-08-23"><div class="day-contents">23</div></td><td class="day past calendar-day-2019-08-24"><div class="day-contents">24</div></td></tr><tr><td class="day today calendar-day-2019-08-25"><div class="day-contents">25</div></td><td class="day calendar-day-2019-08-26"><div class="day-contents">26</div></td><td class="day calendar-day-2019-08-27"><div class="day-contents">27</div></td><td class="day calendar-day-2019-08-28"><div class="day-contents">28</div></td><td class="day calendar-day-2019-08-29"><div class="day-contents">29</div></td><td class="day calendar-day-2019-08-30"><div class="day-contents">30</div></td><td class="day calendar-day-2019-08-31"><div class="day-contents">31</div></td></tr></tbody></table></div></div>
                    </div>
                    <div class="col-lg-5 event-list-block">
                        <div class="cal-day">
                            <span>Today</span>
                            Friday
                        </div>
                        <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 305px;"><ul class="event-list" style="overflow: hidden; width: auto; height: 305px;">
                            <li>Lunch with jhon @ 3:30 <a href="#" class="event-close"><i class="ico-close2"></i></a></li>
                            <li>Coffee meeting with Lisa @ 4:30 <a href="#" class="event-close"><i class="ico-close2"></i></a></li>
                            <li>Skypee conf with patrick @ 5:45 <a href="#" class="event-close"><i class="ico-close2"></i></a></li>
                            <li>Gym @ 7:00 <a href="#" class="event-close"><i class="ico-close2"></i></a></li>
                            <li>Dinner with daniel @ 9:30 <a href="#" class="event-close"><i class="ico-close2"></i></a></li>
            
                        </ul><div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 305px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
                        <input type="text" class="form-control evnt-input" placeholder="NOTES">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <section class="panel">
                    <header class="panel-heading">
                        Chat <span class="tools pull-right">
                    <a href="javascript:;" class="fa fa-chevron-down"></a>
                    <a href="javascript:;" class="fa fa-cog"></a>
                    <a href="javascript:;" class="fa fa-times"></a>
                    </span>
                    </header>
                    <div class="panel-body">
                        <div class="chat-conversation">
                            <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 360px;"><ul class="conversation-list" style="overflow: hidden; width: auto; height: 360px;">
                                <li class="clearfix">
                                    <div class="chat-avatar">
                                        <img src="images/chat-user-thumb.png" alt="male">
                                        <i>10:00</i>
                                    </div>
                                    <div class="conversation-text">
                                        <div class="ctext-wrap">
                                            <i>John Carry</i>
                                            <p>
                                                Hello!
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li class="clearfix odd">
                                    <div class="chat-avatar">
                                        <img src="images/chat-user-thumb-f.png" alt="female">
                                        <i>10:00</i>
                                    </div>
                                    <div class="conversation-text">
                                        <div class="ctext-wrap">
                                            <i>Lisa Peterson</i>
                                            <p>
                                                Hi, How are you? What about our next meeting?
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li class="clearfix">
                                    <div class="chat-avatar">
                                        <img src="images/chat-user-thumb.png" alt="male">
                                        <i>10:00</i>
                                    </div>
                                    <div class="conversation-text">
                                        <div class="ctext-wrap">
                                            <i>John Carry</i>
                                            <p>
                                                Yeah everything is fine
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li class="clearfix odd">
                                    <div class="chat-avatar">
                                        <img src="images/chat-user-thumb-f.png" alt="female">
                                        <i>10:00</i>
                                    </div>
                                    <div class="conversation-text">
                                        <div class="ctext-wrap">
                                            <i>Lisa Peterson</i>
                                            <p>
                                                Wow that's great
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            </ul><div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 360px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
                            <div class="row">
                                <div class="col-xs-9">
                                    <input type="text" class="form-control chat-input" placeholder="Enter your text">
                                </div>
                                <div class="col-xs-3 chat-send">
                                    <button type="submit" class="btn btn-default">Send</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            </div> -->
         <!-- <div class="row">
            <div class="col-md-8">
                <section class="panel">
                    <div class="wdgt-row">
                        <img src="images/weather_image.jpg" height="243" alt="">
                        <div class="wdt-head">
                            weather forecast
                        </div>
                        <div class="country-select">
                            <select class="styled hasCustomSelect" style="-webkit-appearance: menulist-button; width: 82px; position: absolute; opacity: 0; height: 31px; font-size: 12px;">
                                <option>New York </option>
                                <option>London </option>
                                <option>Australia </option>
                                <option>China </option>
                                <option>Canada </option>
                            </select><span class="customSelect styled" style="display: inline-block;"><span class="customSelectInner" style="width: 60px; display: inline-block;">New York </span></span>
                        </div>
                    </div>
            
                    <div class="panel-body">
                        <div class="row weather-full-info">
                            <div class="col-md-3 today-status">
                                <h1>Today</h1>
                                <i class=" ico-cloudy "></i>
                                <div class="degree">37</div>
                            </div>
                            <div class="col-md-9">
                                <ul>
                                    <li>
                                        <h2>Tomorrow</h2>
                                        <i class=" ico-cloudy text-primary"></i>
                                        <div class="statistics">32</div>
                                    </li>
                                    <li>
                                        <h2>Mon</h2>
                                        <i class=" ico-rainy2 text-danger"></i>
                                        <div class="statistics">40</div>
                                    </li>
                                    <li>
                                        <h2>Tue</h2>
                                        <i class=" ico-lightning3 text-info"></i>
                                        <div class="statistics">25</div>
                                    </li>
                                    <li>
                                        <h2>Wed</h2>
                                        <i class=" ico-sun3 text-success"></i>
                                        <div class="statistics">37</div>
                                    </li>
                                    <li>
                                        <h2>Thu</h2>
                                        <i class=" ico-snowy3 text-warning"></i>
                                        <div class="statistics">15</div>
                                    </li>
                                    <li>
                                        <h2>Fri</h2>
                                        <i class=" ico-cloudy "></i>
                                        <div class="statistics">21</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
            
                </section>
            </div>
            
            <div class="col-md-4">
                <div class="profile-nav alt">
                    <section class="panel">
                        <div class="user-heading alt clock-row terques-bg">
                            <h1>December 14</h1>
                            <p class="text-left">2014, Friday</p>
                            <p class="text-left">7:53 PM</p>
                        </div>
                        <ul id="clock">
                            <li id="sec" style="transform: rotate(156deg);"></li>
                            <li id="hour" style="transform: rotate(680deg);"></li>
                            <li id="min" style="transform: rotate(240deg);"></li>
                        </ul>
            
                        <ul class="clock-category">
                            <li>
                                <a href="#" class="active">
                                    <i class="ico-clock2"></i>
                                    <span>Clock</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="ico-alarm2 "></i>
                                    <span>Alarm</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="ico-stopwatch"></i>
                                    <span>Stop watch</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class=" ico-clock2 "></i>
                                    <span>Timer</span>
                                </a>
                            </li>
                        </ul>
            
                    </section>
            
                </div>
            </div>
            </div> -->
         <div class="row">
            <div class="col-md-6">
               <!--notification start-->
               <section class="panel">
                  <header class="panel-heading">
                     Sales Graph <span class="tools pull-right">
                     <a href="javascript:;" class="fa fa-chevron-down"></a>
                     <a href="javascript:;" class="fa fa-cog"></a>
                     <a href="javascript:;" class="fa fa-times"></a>
                     </span>
                  </header>
                  <div class="panel-body">
                      <div class="sales-graph">
                        <canvas id="sales-graph"></canvas>
                     </div> 
                  </div>
               </section>
               <!--notification end-->
            </div>
            <!-- <div class="col-md-6">
               <section class="panel">
                  <header class="panel-heading">
                     To Do List <span class="tools pull-right">
                     <a href="javascript:;" class="fa fa-chevron-down"></a>
                     <a href="javascript:;" class="fa fa-cog"></a>
                     <a href="javascript:;" class="fa fa-times"></a>
                     </span>
                  </header>
                  <div class="panel-body">
                     <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 300px;">
                        <ul class="to-do-list ui-sortable" id="sortable-todo" style="overflow: hidden; width: auto; height: 300px;">
                           <li class="clearfix">
                              <span class="drag-marker">
                              <i></i>
                              </span>
                              <div class="todo-check pull-left">
                                 <input type="checkbox" value="None" id="todo-check">
                                 <label for="todo-check"></label>
                              </div>
                              <p class="todo-title">
                                 Donec quam libero, rutrum non gravida ut
                              </p>
                              <div class="todo-actionlist pull-right clearfix">
                                 <a href="#" class="todo-done"><i class="fa fa-check"></i></a>
                                 <a href="#" class="todo-edit"><i class="ico-pencil"></i></a>
                                 <a href="#" class="todo-remove"><i class="ico-close"></i></a>
                              </div>
                           </li>
                           <li class="clearfix">
                              <span class="drag-marker">
                              <i></i>
                              </span>
                              <div class="todo-check pull-left">
                                 <input type="checkbox" value="None" id="todo-check1">
                                 <label for="todo-check1"></label>
                              </div>
                              <p class="todo-title">
                                 Donec quam libero, rutrum non gravida
                              </p>
                              <div class="todo-actionlist pull-right clearfix">
                                 <a href="#" class="todo-done"><i class="fa fa-check"></i></a>
                                 <a href="#" class="todo-edit"><i class="ico-pencil"></i></a>
                                 <a href="#" class="todo-remove"><i class="ico-close"></i></a>
                              </div>
                           </li>
                           <li class="clearfix">
                              <span class="drag-marker">
                              <i></i>
                              </span>
                              <div class="todo-check pull-left">
                                 <input type="checkbox" value="None" id="todo-check2">
                                 <label for="todo-check2"></label>
                              </div>
                              <p class="todo-title">
                                 Donec quam libero, rutrum non gravida ut
                              </p>
                              <div class="todo-actionlist pull-right clearfix">
                                 <a href="#" class="todo-done"><i class="fa fa-check"></i></a>
                                 <a href="#" class="todo-edit"><i class="ico-pencil"></i></a>
                                 <a href="#" class="todo-remove"><i class="ico-close"></i></a>
                              </div>
                           </li>
                           <li class="clearfix">
                              <span class="drag-marker">
                              <i></i>
                              </span>
                              <div class="todo-check pull-left">
                                 <input type="checkbox" value="None" id="todo-check3">
                                 <label for="todo-check3"></label>
                              </div>
                              <p class="todo-title">
                                 Donec quam libero, rutrum non gravida ut
                              </p>
                              <div class="todo-actionlist pull-right clearfix">
                                 <a href="#" class="todo-done"><i class="fa fa-check"></i></a>
                                 <a href="#" class="todo-edit"><i class="ico-pencil"></i></a>
                                 <a href="#" class="todo-remove"><i class="ico-close"></i></a>
                              </div>
                           </li>
                           <li class="clearfix">
                              <span class="drag-marker">
                              <i></i>
                              </span>
                              <div class="todo-check pull-left">
                                 <input type="checkbox" value="None" id="todo-check4">
                                 <label for="todo-check4"></label>
                              </div>
                              <p class="todo-title">
                                 Donec quam libero, rutrum non gravida ut
                              </p>
                              <div class="todo-actionlist pull-right clearfix">
                                 <a href="#" class="todo-done"><i class="fa fa-check"></i></a>
                                 <a href="#" class="todo-edit"><i class="ico-pencil"></i></a>
                                 <a href="#" class="todo-remove"><i class="ico-close"></i></a>
                              </div>
                           </li>
                           <li class="clearfix">
                              <span class="drag-marker">
                              <i></i>
                              </span>
                              <div class="todo-check pull-left">
                                 <input type="checkbox" value="None" id="todo-check5">
                                 <label for="todo-check5"></label>
                              </div>
                              <p class="todo-title">
                                 Donec quam libero, rutrum non gravida ut
                              </p>
                              <div class="todo-actionlist pull-right clearfix">
                                 <a href="#" class="todo-done"><i class="fa fa-check"></i></a>
                                 <a href="#" class="todo-edit"><i class="ico-pencil"></i></a>
                                 <a href="#" class="todo-remove"><i class="ico-close"></i></a>
                              </div>
                           </li>
                           <li class="clearfix">
                              <span class="drag-marker">
                              <i></i>
                              </span>
                              <div class="todo-check pull-left">
                                 <input type="checkbox" value="None" id="todo-check6">
                                 <label for="todo-check6"></label>
                              </div>
                              <p class="todo-title">
                                 Donec quam libero, rutrum non gravida ut
                              </p>
                              <div class="todo-actionlist pull-right clearfix">
                                 <a href="#" class="todo-done"><i class="fa fa-check"></i></a>
                                 <a href="#" class="todo-edit"><i class="ico-pencil"></i></a>
                                 <a href="#" class="todo-remove"><i class="ico-close"></i></a>
                              </div>
                           </li>
                        </ul>
                        <div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 262.391px;"></div>
                        <div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>
                     </div>
                     <div class="todo-action-bar">
                        <div class="row">
                           <div class="col-xs-4 btn-todo-select">
                              <button type="submit" class="btn btn-default"><i class="fa fa-check"></i> Select All</button>
                           </div>
                           <div class="col-xs-4 todo-search-wrap">
                              <input type="text" class="form-control search todo-search pull-right" placeholder=" Search">
                           </div>
                           <div class="col-xs-4 btn-add-task">
                              <button type="submit" class="btn btn-default btn-primary"><i class="fa fa-plus"></i> Add Task</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </section>
            </div> -->
             <div class="col-md-6">
               <!--todolist start-->
               <section class="panel">
                  <header class="panel-heading">
                     User Log <span class="tools pull-right">
                     <a href="javascript:;" class="fa fa-chevron-down"></a>
                     <a href="javascript:;" class="fa fa-cog"></a>
                     <a href="javascript:;" class="fa fa-times"></a>
                     </span>
                  </header>
                  <div class="panel-body">
                         <ul class="list-group" v-show="userLog.length" v-for="(user, index) in userLog" :key="user.id">
                              <li style="list-style: none;margin-bottom: -12px;    list-style: none;
    margin-bottom: -12px;
    border: 1px dotted;
    padding-top: 5px;
    padding-bottom: 5px;
    padding-left: 5px;
    padding-right: 5px;"> {{index + 1}}. {{ user.name }} ( {{ user.updated_at | moment("MMMM Do YYYY, h:mm: a") }} ) <i v-if="curuser.id == user.id" class="fa fa-user"  style="color:green;float: right;" aria-hidden="true"></i> <i v-if="curuser.id != user.id" class="fa fa-user"  style="color:red;float: right;" aria-hidden="true"></i></li>
                           </ul>
                      </div>
               </section>
               <!--todolist end-->
            </div>
         </div>
      </section>
      <div v-if="!success" class="row" style="margin:0 0px;margin-left: 15px;padding-right: 15px;">
         <div class="col-md-12 table-responsive" style="background-color:white;">
            <h3 class="text-left report_header box-header with-border">Latest Invoice</h3>
            <section class="panel">
               <header class="panel-heading">
                  Recent 10 Invoices
                  <span class="tools pull-right">
                  <a href="javascript:;" class="fa fa-chevron-down"></a>
                  </span>
               </header>
               <div class="panel-body" style="display: block;">
                  <table class="table table-hover general-table">
                     <thead>
                        <tr>
                           <th >#</th>
                           <th >Invoice No</th>
                           <th >Date</th>
                           <th >Amount</th>
                           <th >Client Name</th>
                           <th >Client Phone</th>
                           <th >Payment Status</th>
                           <th >Options</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr v-show="invoices.length" v-for="(invoice, index) in invoices" :key="invoice.id">
                           <th scope="row">{{index + 1}}</th>
                           <td>FSTT{{ invoice.invoice_no }}</td>
                           <td>{{ invoice.invoice_date }}</td>
                           <td>{{ invoice.total_receivable }}</td>
                           <td>{{ invoice.name }}</td>
                           <td>{{ invoice.phone }}</td>
                           <td class="text-center"><span v-if="invoice.status == 'paid'" class="label label-success" style="font-size:14px;text-transform:uppercase"> {{ invoice.status }}</span>
                              <span v-if="invoice.status == 'due'" class="label label-danger" style="font-size:14px;text-transform:uppercase"> {{ invoice.status }}</span>
                              <span v-if="invoice.status == 'partial'" class="label label-warning" style="font-size:14px;text-transform:uppercase"> {{ invoice.status }}</span>
                           </td>
                           <td>
                              <!-- <router-link :to="{ name: 'InvoiceDetails', params: { id: invoice.invoice_no } }" class="btn btn-success">View</router-link>
                              <a class="btn btn-warning" target="_blank" :href="'/api/v1/makepdf/' + invoice.invoice_no + '/print'">Print Customer Copy</a>
                              <a class="btn btn-danger" target="_blank" :href="'/api/v1/makepdf/' + invoice.invoice_no + '/office'">Print Office Copy</a>
                              <a class="btn btn-primary" target="_blank" :href="'/api/v1/makepdf/' + invoice.invoice_no ">Download</a>
                              <a href="#" class="btn btn-danger" title="Delete" v-on:click="DeleteInvoice(invoice.invoice_no)"><i class="fa fa-trash-o-o"></i></a> -->
                              <!-- <select name="" id="" class="form-control">
                                  <option value=""><router-link :to="{ name: 'InvoiceDetails', params: { id: invoice.invoice_no } }" class="btn btn-success">View</router-link></option>
                                  <option value=""><a target="_blank" :href="'/api/v1/makepdf/' + invoice.invoice_no + '/print'">Print Customer Copy</a></option>
                              </select> -->
                                <div class="dropdown">
                                    <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Options
                                    <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li> <router-link :to="{ name: 'InvoiceDetails', params: { id: invoice.invoice_no } }">View</router-link></li>
                                        <li><a target="_blank" :href="'/api/v1/makepdf/' + invoice.invoice_no + '/print'">Print Customer Copy</a></li>
                                        <li><a  target="_blank" :href="'/api/v1/makepdf/' + invoice.invoice_no + '/office'">Print Office Copy</a></li>
                                        <li><a  target="_blank" :href="'/api/v1/makepdf/' + invoice.invoice_no ">Download</a></li>
                                        <li><a  href="javascript:;" title="Delete" v-on:click="DeleteInvoice(invoice.invoice_no)">Delete</a> </li>
                                    </ul>
                                    </div>
                           </td>
                        </tr>
                        <tr v-show="!invoices.length">
                           <td colspan="8">
                              <div class="alert alert-danger text-center" role="alert">
                                 Opps! Sorry no data found
                              </div>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </section>
         </div>
      </div>
   </div>
</template>
<script>
   import { Line } from "vue-chartjs";
   export default {
     name: "report",
     extends: Line,
     data() {
       return {
         chart: null,
         labelSalesDate: [],
         TotalIncomes: [],
         TotalExpenses: [],
         TotalDue: [],
         TotalSale: [],
         TotalSevenDaysSale: [],
         TotalSevenDaysDue: [],
         TotalSevenDaysIncome: [],
         TotalSevenDaysExpense: [],
         TotalSevenDaysDeposit: [],
         TotalSevenDaysWithdraw: [],
         TotalSevenDaysTransfer: [],
         totalTicket:[],
         totalCustomer:[],
         totalService:[],
         totalProfit:[],
         invoices: [],
         totalGDSSale:[],
         totalServiceSale:[],
         totalThirdPartySale:[],
         totalSalesAll:[],
   
         MonthlyGDSSale:[],
         MonthlyServiceSale:[],
         MonthlyThirdPartySale:[],
         MonthlySalesAll:[],
   
   
         PercentageGDS:null,
         PercentageService:null,
         PercentageThirdParty:null,
   
         PercentageGDSMonthly:null,
         PercentageServiceMonthly:null,
         PercentageThirdPartyMonthly:null,
         userLog: [],
          success: null,
          company: {
   
                   name:'',
                   address : '',
                   address2 : '',
                   phone1: '',
                   phone2: '',
                   email : '',
                   id : ''
            },
            fields: {
   
               inv_id:''
            },
             loadSuccessMessage: '',
            errors: {},
         isLoading: true,
         PartialPayment: '',
          loaded: true,
           account_numbers:[],
          curuser: JSON.parse(localStorage.getItem('user')),
           invoiceTypeService : false,
           invoiceTypeProduct : false,
            sections: [
               { label: 'GDS', value: null }, 
               { label: 'Service', value: null}, 
               { label: 'Third Party', value: null}
            ]
           
       };
     },
   
     mounted() {
       this.ReportData();
       this.ReportDataSevenDays();
       this.renderSevenDaysSalesReport();
      //  this.renderSevenDaysIncomeReport();
      //  this.renderSevenDaysExpenseReport();
       this.renderSevenDaysDepositReport();
       this.renderInvoice();
       this.renderUserLog();
       this.CompanyInfo();
       this.TotalTSPC();
       this.TotalSalesForOneDay();
       this.TotalSalesForOneMonth();
     },
     methods: {

      renderSevenDaysSalesReport() {
          this.success = false;
         axios
           .get("/api/v1/sales-report")
           .then(response => {
             const labels = [];
             const formatedData = {};
              if(response.data == ''){
                var canvas = document.getElementById("sales-graph");
                 var ctx = canvas.getContext("2d");
                 canvas.height = 300;
   
                 ctx.font = "20px Arial";
                 ctx.fillText("No Data Found", 10, 50);
                 return;
   
             }
             for (let i = 6; i >= 0; i--) {
               let date = moment(Date.now() - i * 24 * 3600 * 1000).format(
                 "YYYY-MM-DD"
               );
               labels.push(date);
               formatedData[date] = 0;
             }
            
             response.data.data.forEach(item => {
               formatedData[item.sales_date] =
                 formatedData[item.sales_date] + item.total_sale;
             });
   
             const data = [];
   
             Object.keys(formatedData).forEach(date => {
               data.push(formatedData[date]);
             });
             
   
             const config = {
               type: "line",
               responsive: false,
               data: {
                 labels,
                 datasets: [
                   {
                     label: "Sales: Last 7 active days",
                     backgroundColor: "rgba(111,228,113,.2)",
                     borderColor: "rgba(111,228,113,1)",
                     borderWidth: 1,
                     data
                   }
                 ]
               }
             };
             this.chart = new Chart(jQuery(this.$el).find("#sales-graph"), config);
           })
           .catch(e => {
             console.log(e);
             this.$Progress.fail();
           });
       },  
      //  renderSevenDaysSalesReport() {
      //     this.success = false;
      //    axios
      //      .get("/api/v1/sales-report")
      //      .then(response => {
      //          console.log(response)
      //        const labels = [];
      //        const formatedData = {};
      //         if(response.data == ''){
      //           var canvas = document.getElementById("sales-graph");
      //            var ctx = canvas.getContext("2d");
      //            canvas.height = 100;
   
      //            ctx.font = "10px Arial";
      //            ctx.fillText("No Data Found", 10, 50);
      //            return;
   
      //        }
      //        for (let i = 2; i >= 0; i--) {
      //          let date = moment(Date.now() - i * 24 * 3600 * 1000).format(
      //            "DD"
      //          );
      //          labels.push(date);
      //          formatedData[date] = 0;
      //        }
            
      //        response.data.data.forEach(item => {
      //          formatedData[item.sales_date] =
      //            formatedData[item.sales_date] + item.total_sale;
      //        });
   
      //        const data = [];
   
      //        Object.keys(formatedData).forEach(date => {
      //          data.push(formatedData[date]);
      //        });
             
   
      //        const config = {
      //          type: "line",
      //          responsive: false,
      //          data: {
      //            labels,
      //            datasets: [
      //              {
      //                label: "Expense: Last 3 active days",
      //                backgroundColor: "rgba(111,228,113,.2)",
      //                borderColor: "rgba(111,228,113,1)",
      //                borderWidth: 1,
      //                data
      //              }
      //            ]
      //          }
      //        };
      //        this.chart = new Chart(jQuery(this.$el).find("#sales-graph"), config);
      //      })
      //      .catch(e => {
      //        console.log(e);
      //        this.$Progress.fail();
      //      });
      //  },
       
      //  renderSevenDaysIncomeReport() {
      //     this.success = false;
      //    axios
      //      .get("/api/v1/income-report")
      //      .then(response => {
      //        const labels = [];
      //        const formatedData = {};
      //        if(response.data == ''){
      //           var canvas = document.getElementById("income-graph");
      //            var ctx = canvas.getContext("2d");
      //            ctx.font = "30px Arial";
      //            ctx.fillText("No Data Found", 10, 50);
      //            return;
   
      //        }
   
      //        for (let i = 6; i >= 0; i--) {
      //          let date = moment(Date.now() - i * 24 * 3600 * 1000).format(
      //            "YYYY-MM-DD"
      //          );
      //          labels.push(date);
      //          formatedData[date] = 0;
      //        }
   
      //        response.data.data.forEach(item => {
      //          formatedData[item.income_date] =
      //            formatedData[item.income_date] + item.total_income;
      //        });
   
      //        const data = [];
   
      //        Object.keys(formatedData).forEach(date => {
      //          data.push(formatedData[date]);
      //        });
   
      //        const config = {
      //          type: "line",
      //          responsive: false,
      //          data: {
      //            labels,
      //            datasets: [
      //              {
      //                label: "Sales: Last 7 active days",
      //                backgroundColor: "rgb(0,0,255,0.5)",
      //                borderColor: "rgb(0,0,255,0.1)",
      //                borderWidth: 1,
      //                data
      //              }
      //            ]
      //          }
      //        };
      //        this.chart = new Chart(jQuery(this.$el).find("#income-graph"), config);
      //      })
      //      .catch(e => {
      //        console.log(e);
      //        this.$Progress.fail();
      //      });
      //  },
      //  renderSevenDaysExpenseReport() {
      //     this.success = false;
      //    axios
      //      .get("/api/v1/expense-report")
      //      .then(response => {
      //        const labels = [];
      //        const formatedData = {};
      //        if(response.data == ''){
      //           var canvas = document.getElementById("expense-graph");
      //            var ctx = canvas.getContext("2d");
      //            ctx.font = "30px Arial";
      //            ctx.fillText("No Data Found", 10, 50);
      //            return;
   
      //        }
      //        for (let i = 6; i >= 0; i--) {
      //          let date = moment(Date.now() - i * 24 * 3600 * 1000).format(
      //            "YYYY-MM-DD"
      //          );
      //          labels.push(date);
      //          formatedData[date] = 0;
      //        }
   
      //        response.data.data.forEach(item => {
      //          formatedData[item.expense_date] =
      //            formatedData[item.expense_date] + item.total_expense;
      //        });
   
      //        const data = [];
   
      //        Object.keys(formatedData).forEach(date => {
      //          data.push(formatedData[date]);
      //        });
   
      //        const config = {
      //          type: "line",
      //          responsive: false,
      //          data: {
      //            labels,
      //            datasets: [
      //              {
      //                label: "Sales: Last 7 active days",
      //                backgroundColor: "rgb(255,0,0,0.5)",
      //                borderColor: "rgb(255,0,0,0.1)",
      //                borderWidth: 1,
      //                data
      //              }
      //            ]
      //          }
      //        };
      //        this.chart = new Chart(jQuery(this.$el).find("#expense-graph"), config);
      //      })
      //      .catch(e => {
      //        console.log(e);
      //        this.$Progress.fail();
      //      });
      //  },
    CompanyInfo(){
   
             axios.get('/api/v1/companny-info')
             .then(response => {
                
                this.company.name = response.data.data[0].company_name
                this.company.address = response.data.data[0].company_address
                this.company.address2 = response.data.data[0].company_address2
                this.company.phone1 = response.data.data[0].company_phone_one
                this.company.phone2 = response.data.data[0].company_phone_two
                this.company.email = response.data.data[0].company_email
                
              
                  this.$Progress.finish()
             })
             .catch(e => {
   
                 console.log(e)
                  this.$Progress.fail()
             })
       },
       ReportData() {
          this.success = false;
         this.$Progress.start();
          this.isLoading = true;
         axios
           .get("/api/v1/all-reports")
           .then(response => {
             
             if(response != ''){this.isLoading = true;}
             this.TotalIncomes = response.data.income[0].TotalIncome;
             this.TotalExpenses = response.data.expense[0].TotalExpense;
             this.TotalDue = response.data.due[0].TotalDue;
             this.TotalSale = response.data.sale[0].TotalSale;
           })
           .catch(e => {
             console.log(e);
             this.$Progress.fail();
           });
       },
       TotalTSPC() {
          this.success = false;
         this.$Progress.start();
          this.isLoading = true;
         axios
           .get("/api/v1/total-tspc")
           .then(response => {
             
           
   
             this.totalTicket = response.data.total_ticket[0].total_ticket;
             this.totalCustomer = response.data.total_customer[0].total_customer;
             this.totalService = response.data.total_service[0].total_service;
             this.totalProfit = response.data.total_profit[0].total_profit;
           
            console.log(this.totalTicket);
           })
           .catch(e => {
             console.log(e);
             this.$Progress.fail();
           });
       },
       TotalSalesForOneDay() {
          this.success = false;
         axios
           .get("/api/v1/daily-sales-state")
           .then(response => {
             
             this.totalGDSSale = response.data.gds_sales[0].gds_sales;
             this.totalServiceSale = response.data.service_sales[0].service_sales;
             this.totalThirdPartySale = response.data.thrid_party_sales[0].thrid_party_sales;
             this.totalSalesAll = response.data.total_sales[0].total_sales;
   
            this.PercentageGDS = ( 100 / this.totalSalesAll ) * this.totalGDSSale ;
            this.PercentageService = ( 100 / this.totalSalesAll ) * this.totalServiceSale ;
            this.PercentageThirdParty = ( 100 / this.totalSalesAll ) * this.totalThirdPartySale ;
             
            //alert(this.PercentageService + this.PercentageThirdParty + this.PercentageGDS)
            //console.log(this.totalSalesAll);
           })
           .catch(e => {
             console.log(e);
             this.$Progress.fail();
           });
       },
       TotalSalesForOneMonth() {
          this.success = false;
         axios
           .get("/api/v1/monthly-sales-state")
           .then(response => {
             
             this.MonthlyGDSSale = response.data.gds_sales[0].gds_sales;
             this.MonthlyServiceSale = response.data.service_sales[0].service_sales;
             this.MonthlyThirdPartySale = response.data.thrid_party_sales[0].thrid_party_sales;
             this.MonthlySalesAll = response.data.total_sales[0].total_sales;
   
            this.PercentageGDSMonthly = ( 100 / this.MonthlySalesAll ) * this.MonthlyGDSSale ;
            this.PercentageServiceMonthly = ( 100 / this.MonthlySalesAll ) * this.MonthlyServiceSale ;
            this.PercentageThirdPartyMonthly = ( 100 / this.MonthlySalesAll ) * this.MonthlyThirdPartySale ;
   
            if(this.sections[0].label == 'GDS'){
                
                this.sections[0].value = Math.round(this.PercentageGDSMonthly)
            }
            if(this.sections[1].label == 'Service'){
               
                this.sections[1].value = Math.round(this.PercentageServiceMonthly)
            }
            if(this.sections[2].label == 'Third Party'){
               
                this.sections[2].value = Math.round(this.PercentageThirdPartyMonthly)
            }
           
             
            //alert(Number((this.PercentageGDSMonthly).toFixed(1)))
            //alert(Math.round(this.PercentageGDSMonthly))
            //console.log(response);
           })
           .catch(e => {
             console.log(e);
             this.$Progress.fail();
           });
       },
       ReportDataSevenDays() {
           this.success = false;
         this.$Progress.start();
         axios
           .get("/api/v1/seven-days-reports")
           .then(response => {
             this.TotalSevenDaysIncome =
               response.data.seven_days_income[0].SevenDaysIncome;
             this.TotalSevenDaysExpense =
               response.data.seven_days_expense[0].SevenDaysExpense;
             this.TotalSevenDaysDue = response.data.seven_days_due[0].SevenDaysDue;
             this.TotalSevenDaysSale =
               response.data.seven_days_sale[0].SevenDaysSale;
           })
           .catch(e => {
             console.log(e);
             this.$Progress.fail();
           });
       },
   
       renderSevenDaysDepositReport(){
          this.success = false;
         this.$Progress.start();
         axios
           .get("/api/v1/dashboard_transaction")
           .then(response => {
             this.TotalSevenDaysDeposit = response.data.deposit[0].deposit;
             this.TotalSevenDaysWithdraw = response.data.withdraw[0].withdraw;
             this.TotalSevenDaysTransfer = response.data.transfer[0].transfer_amount;
             
             console.log(this.TotalSevenDaysDeposit);
           })
           .catch(e => {
             console.log(e);
             this.$Progress.fail();
           });
       },
       renderInvoice(){
         this.success = false;
         this.$Progress.start();
         axios
           .get("/api/v1/invoice-list")
           .then(response => {
             this.invoices = response.data.data;
            //  console.log(this.invoices);
           })
           .catch(e => {
             console.log(e);
             this.$Progress.fail();
           });
       },
        renderUserLog(){
           this.success = false;
         this.$Progress.start();
         axios
           .get("/api/v1/user-log")
           .then(response => {
             
             
             this.userLog = response.data.data;
            //  console.log(this.userLog);
           })
           .catch(e => {
             console.log(e);
             this.$Progress.fail();
           });
       },
     
       
     },
     filters: {}
   };


</script>
