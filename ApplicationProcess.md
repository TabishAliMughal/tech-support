# **UI Search Component Documentation**
---
---
---
This Is A UI Search Component That Makes Search And Filtering More Easy Especialy On Mobile And Small Screens

## Follow These Steps To Apply UI Search Component

### Installation And Configuration
---
#### 01
- Open SVN And Navigate To The Project
- Get The Project URL From SVN
#### 02
- Open Your Project And Navigate To **composer.json**
- Navigate To The Require Tag And Add The Following Code :
```bash
  "<Your Repository>/techsupportchat": "v1.0"
```
- Now , Navigate To The Repository Tag Or Create If Not Exist
- In THe Require Tag , Add Your Repository URL You Got In **Step 1**  Like This:
```bash
  "repositories": [
        {
          "type":"package",
          "package": {
                "name": "<Your Repository>/techsupportchat",
                "version":"v1.0",
                "source": {
                      "url": "<-- Your URL -->",
                      "type": "svn",
                      "reference":"v1.0"
                }
            }
        }
  ],
```
- Then Save And Close The **composer.json** File
    
#### 03
- Now , Create A Service Provider By Executing The Following In CMD
```bash
    $ php artisan make:provider TechSupportChatProvider
```
- After Executing The Command , You Will See A New File Named **TechSupportChatProvider"** in *App>Providers* Of Your Project Directory
- Open The **TechSupportChatProvider** File You Just Created And Navigate To boot() Function
- Add The Following Lines In It:
```bash
    $this->publishes([
        base_path().'/vendor/tabishalimughal/techsupportchat/assets' => public_path('<Path To Assets>/techsupportchat'),
        base_path().'/vendor/tabishalimughal/techsupportchat/views' => base_path('<Path To Views>/techsupportchat'),
        base_path().'/vendor/tabishalimughal/techsupportchat/models' => base_path('<Path To Models>/techsupportchat'),
        base_path().'/vendor/tabishalimughal/techsupportchat/controllers' => base_path('<Path To Controller>/techsupportchat'),
        base_path().'/vendor/tabishalimughal/techsupportchat/migrations' => base_path('app/Database/migrations'),
    ], 'techsupportchat');
```
- Save The File And Close It
#### 04
- Now Its Time To Register The Service Provider We Just Created
- To Register The Service Provider , Navigate To *config>app.php* From Your Base Directory And Open It
- Scroll Down And Find The List Of Providers
- Add The Relative Path Of Your Provider At The Bottom Of List As Follows
```bash
    App\Providers\TechSupportChatProvider::class,
```
#### 05
- Now Its Time To Install The Component
- Open CMD In Your Base Directory And Run The Following Commands
```bash
    $ composer clear-cache
    $ composer install
    $ composer update
```
- After The Successfull Execution Of Commands Open The vendor In Your Base Directory , You Will See A Folder With Same Name As Your Repository On **SVN**
    - That Was What We Configured On **Step 2**
- Now , Run The Fillowing Command
```bash
    $ php artisan vendor:publish --tag=techsupportchat
```
- After The Successfull Execution Of Command , You Will See That You Have New Files In Your Assets And Views Directories
    - That Was What We Configured On **Step 3** And **Step 4**

### Implementation
---
##### Create Seprate Filter Files
- In Your Project Create Folders Of Every Controller And Put All Views Inside Them As Follows (Best Practice)
```command
    |
    |--Shops
    |   |-- Index.blade.php         # Index File For All Data
    |   |-- Form.blade.php          # Form Template
    |   |-- Edit.blade.php          # Edit Template (If You Are Not ReUsing Form Template)
    |   |-- Filters.blade.php       # Component Required File
    |--Products
    |   |-- Index.blade.php         # Index File For All Data
    |   |-- Form.blade.php          # Form Template
    |   |-- Edit.blade.php          # Edit Template (If You Are Not ReUsing Form Template)
    |   |-- Filters.blade.php       # Component Required File
```
- Create A File Named **filters.blade.php** in Every Directory.
- Cut Filter Form Including All Its Fields And Paste Them In The **filters.blade.php** file.
- Now , You Have You Are Index Files Neat And All Filters Have Been Moved To **filters.blade.php** files.
- Next , Open Edit Your **filter.blade.php** File And Add The Following Code :
```console
    @extends('<!--Path To filter_canvas_template.blade.blade.php-->')
    @section('canvas-title')
        <!--Heading Of The Side Panel-->
    @endsection
    @section('canvas-body')
        <!--Your All Filteration Code Goes Here Including Form-->
    @endsection
```
- Include Every Filter File In Your **index.blade.php** or **list.blade.php** File As Follows
```bash
	@include('Path To Your Filter Template You Just Created')
```
- Your Filter Files Are Ready
##### Configure Base File For Static
- Open Your Base File Which Is Extended By All Templates
- Create CSS Links Inside _<head>_ tag as follows
```console
	<link href="<!--Path To Your Assets Folder-->/uisearch/css/canvas.css" rel="stylesheet">
	<link href="<!--Path To Your Assets Folder-->/uisearch/css/filter-grid.css" rel="stylesheet">
	<link href="<!--Path To Your Assets Folder-->/uisearch/css/tooltip.css" rel="stylesheet">
```
- Create Javascript Links Inside _<head>_ tag as follows
```console
	<script src="<!--Path To Your Assets Folder-->/uisearch/js/canvas.js"></script>
	<script src="<!--Path To Your Assets Folder-->/uisearch/js/jquery.js"></script>
	<script src="<!--Path To Your Assets Folder-->/uisearch/js/bootstrap.js"></script>
```
- Start And Run Project To See If There Are Any Conflicts (If There Are , Move Your JS And CSS Files Up And Down To Adjust Them)
- Your Project Is All Set Up
    ##### Bugs And Fixes
    - When You Submit After Filling The Filter Form , It Your Form Is Not Submiting And Logging You Out , Please Change Your Form Submition Button To Input (type="text")
##### Configure Grid On Top Of Table To Indicate Filters
^You^ ^Can^ ^Only^ ^Use^ ^This^ ^Grid^ ^If^ ^You^ ^Are^ ^Using^ **^Get^** ^Method^ ^For^ ^Filteration^
- In Your Project Static Files , Add These Two Variables Anywhere In :root
    - **--filter-outline** : # Any Color For Filter Outline
    - **--filter-background** : # Any Collor For Filter Background
- Create A New File Named **filter_grid.blade.php** In Every Directory Containing **filters.blade.php**
- Add The Following Code In The File
```console
@extends('./uisearch/filter_grid_template')
@section('fields')
    @if(!Request::get(<!--Request-->) && <!--All Patterens From Request-->  )
        <div style="padding-right:15px;" onclick="event.stopPropagation()" >
            <div class="position-relative">
                <div>
                    <label class="text-dark" style="font-size:10px;margin-bottom:0px;font-weight:bold;display:inline-block">No Filters Applied</label>
                    | Click <a type="button" style="cursor:pointer;" data-bs-toggle="offcanvas" data-bs-target="#filterCanvas" aria-controls="filterCanvas">Here</a> To Apply Filters <i class="fa fa-filter"></i>
                </div>
            </div>
        </div>
    @endif
    @if(Request::get(<!--Request-->) || <!--All Patterens From Request--> )
        <div style="padding-right:15px;" onclick="event.stopPropagation()" >
            <div class="position-relative" style="top:10px">
                <div style="display:inline-block;max-width:250px;" >
                    <span style="display:flex">
                        <a type="button" style="cursor:pointer;display:inline-block;width:55px" data-bs-toggle="offcanvas" data-bs-target="#filterCanvas" aria-controls="filterCanvas">
                            EditFilters
                        </a>
                    </span>
                </div>
            </div>
        </div>
    @endif
@endsection
```
- Link **filter_grid.blade.php** File In All Of Your Lists And Indexes On Top Of Table Where You Want To Add This Grid As Follows :
```console
    @include(<!--Path To Your Grid Template-->)
```
- Create Grid Tags To Indicate The Applied Filter Head And Value (It Will Be Repeated For All Fields In Your **filters.blade.php** template)
```console
    @if(Request::get(<!--Field-->))
        <div style="padding-right:15px;display:inline-block" onclick="event.stopPropagation()" >
            <div class="position-relative" style="background-color: var(--filter-outline);border-radius:15%;padding:5px">
                <div>
                    <label for="ControllerID" class="text-dark" style="font-size:10px;margin-bottom:0px;font-weight:bold;">Controller</label>
                </div>
                <div class="filter-fields" style="display:inline-block;max-width:250px;" >
                    <?php $dict = <!--Your Data From API-->; ?>
                    @foreach($dict as $data)
                        @if($data-><!--DataID--> == Request::get(<!--Field-->))
                            {{ $data-><!--DataValue--> }}
                            <input type="text" name="<!--FieldID-->" hidden>
                        @endif
                    @endforeach
                </div>
                <span onclick="<!--FieldID-->" class="position-absolute filter-remove-btn">
                    <span class="fa fa-times-circle" style="cursor:pointer;" onclick="removeFilter(<!--FieldID-->)"></span>
                </span>
            </div>
        </div>
    @endif
```
- Please Note **ALL NAMES SHOULD BE SAME AS WRITEN** eg:(replace all field IDs with Your Field ID) Because These Are Case Sensitive
- Save The File And Reload The Project , Everything Should Be Working
##### Indicating Applied Filters On Table Header
- Open Your Index Template And Identify The Table Headers Which Are Includen In Filters
- Give Their **th** Or **td** Two New Attributes As Follows :
```console
    <td data-id="<!--Data ID From API-->" url-id="<!--ID In URL-->"><!--Field Name--></td>
```
- After Doing That , Opem Your App And Refresh The Page And Apply Some Filters , You Should See A New Grid On Top Of The Table With Indication Of Applied Filters And Table Headers Had Changed Their Color.
- Hover On Any Table Header And You Will See A Tooltip With Name Of Applied Filters.

----------
