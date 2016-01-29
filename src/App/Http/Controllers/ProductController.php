<?php namespace Redooor\Redminportal\App\Http\Controllers;

use Lang;
use Redooor\Redminportal\App\Http\Traits\SorterController;
use Redooor\Redminportal\App\Models\Product;
use Redooor\Redminportal\App\Models\Category;
use Redooor\Redminportal\App\Models\Image;
use Redooor\Redminportal\App\Models\Translation;
use Redooor\Redminportal\App\Models\Tag;
use Redooor\Redminportal\App\Helpers\RImage;
use Redooor\Redminportal\App\Classes\Weight;
use Redooor\Redminportal\App\Classes\Volume;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

class ProductController extends Controller
{
    private $weight_units;
    private $volume_units;
    
    use SorterController;

    public function __construct(Product $model)
    {
        $this->weight_units = Weight::getUnits();
        $this->volume_units = Volume::getUnits();
        
        $this->model = $model;
        $this->sortBy = 'name';
        $this->orderBy = 'asc';
        $this->perpage = config('redminportal::pagination.size');
        $this->pageView = 'redminportal::products.view';
        $this->pageRoute = 'admin/products';
        
        // For sorting
        $this->query = $this->model
            ->whereNotExists(function ($query) {
                // Get all products but not variants
                $query->select(\DB::raw(1))
                      ->from('product_variant')
                      ->whereRaw('product_variant.variant_id = products.id');
            })
            ->LeftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name');
    }

    public function getIndex()
    {
        $models = $this->query->orderBy($this->sortBy, $this->orderBy)->paginate($this->perpage);
        
        $data = [
            'models' => $models,
            'sortBy' => $this->sortBy,
            'orderBy' => $this->orderBy
        ];

        return view('redminportal::products/view', $data);
    }

    public function getCreate()
    {
        $categories = Category::where('active', true)
            ->where('category_id', 0)
            ->orWhere('category_id', null)
            ->orderBy('name')
            ->get();
        
        
        
        $data = array(
            'categories' => $categories,
            'weight_units' => $this->weight_units,
            'volume_units' => $this->volume_units
        );
        
        return view('redminportal::products/create', $data);
    }
    
    public function getEdit($sid)
    {
        // Find the product using the user id
        $product = Product::find($sid);

        // No such id
        if ($product == null) {
            $errors = new \Illuminate\Support\MessageBag;
            $errors->add('errorNoSuchProduct', Lang::get('redminportal::messages.error_no_such_product'));
            return redirect('/admin/products')->withErrors($errors);
        }

        $categories = Category::where('active', true)
            ->where('category_id', 0)
            ->orWhere('category_id', null)
            ->orderBy('name')
            ->get();

        $tagString = "";
        foreach ($product->tags as $tag) {
            if (! empty($tagString)) {
                $tagString .= ",";
            }

            $tagString .= $tag->name;
        }

        $translated = array();
        foreach ($product->translations as $translation) {
            $translated[$translation->lang] = json_decode($translation->content);
        }
        
        $data = array(
            'product' => $product,
            'translated' => $translated,
            'categories' => $categories,
            'tagString' => $tagString,
            'imagine' => new RImage,
            'weight_units' => $this->weight_units,
            'volume_units' => $this->volume_units
        );
        
        return view('redminportal::products/edit', $data);
    }
    
    public function getCreateVariant($product_id)
    {
        $product = Product::find($product_id);
        // No such id
        if ($product == null) {
            $errors = new \Illuminate\Support\MessageBag;
            $errors->add('errorNoSuchProduct', Lang::get('redminportal::messages.error_no_such_product'));
            return redirect('/admin/products')->withErrors($errors);
        }
        
        $categories = Category::where('active', true)
            ->where('category_id', 0)
            ->orWhere('category_id', null)
            ->orderBy('name')
            ->get();
        
        $data = array(
            'categories' => $categories,
            'product_id' => $product_id,
            'weight_units' => $this->weight_units,
            'volume_units' => $this->volume_units
        );

        return view('redminportal::products/create-variant', $data);
    }
    
    public function getEditVariant($product_id, $sid)
    {
        // Find the product using the user id
        $product = Product::find($sid);

        // No such id
        if ($product == null) {
            $errors = new \Illuminate\Support\MessageBag;
            $errors->add('errorNoSuchProduct', Lang::get('redminportal::messages.error_no_such_product'));
            return view('redminportal::products/edit-variant')->withErrors($errors);
        }

        $categories = Category::where('active', true)
            ->where('category_id', 0)
            ->orWhere('category_id', null)
            ->orderBy('name')
            ->get();

        $tagString = "";
        foreach ($product->tags as $tag) {
            if (! empty($tagString)) {
                $tagString .= ",";
            }

            $tagString .= $tag->name;
        }

        $translated = array();
        foreach ($product->translations as $translation) {
            $translated[$translation->lang] = json_decode($translation->content);
        }
        
        $data = array(
            'product_id' => $product_id,
            'product' => $product,
            'translated' => $translated,
            'categories'=> $categories,
            'tagString'=> $tagString,
            'imagine' => new RImage,
            'weight_units' => $this->weight_units,
            'volume_units' => $this->volume_units
        );

        return view('redminportal::products/edit-variant', $data);
    }
    
    public function getViewVariant($sid)
    {
        // Find the product using the user id
        $product = Product::find($sid);

        // No such id
        if ($product == null) {
            $errors = new \Illuminate\Support\MessageBag;
            $errors->add('errorNoSuchProduct', Lang::get('redminportal::messages.error_no_such_product'));
            return view('redminportal::products/view-variant')->withErrors($errors);
        }

        $translated = array();
        foreach ($product->translations as $translation) {
            $translated[$translation->lang] = json_decode($translation->content);
        }

        return view('redminportal::products/view-variant')
            ->with('product', $product)
            ->with('translated', $translated)
            ->with('imagine', new RImage);
    }
    
    /*
     * Return a table of variants belonging to the given product id
     * @param integer product id
     * @return view
     */
    public function getListVariants($sid)
    {
        $product = Product::find($sid);
        
        // No such id
        if ($product == null) {
            $errors = new \Illuminate\Support\MessageBag;
            $errors->add('errorNoSuchProduct', Lang::get('redminportal::messages.error_no_such_product'));
            return redirect('/admin/products')->withErrors($errors);
        }
        
        $data = array(
            'variantParent' => $product,
            'variants' => $product->variants,
            'imagine' => new RImage
        );
        return view('redminportal::products/list-variants', $data);
    }
    
    /*
     * Delete a variant
     * @param integer id
     * @return json (bool status, string message)
     */
    public function getDeleteVariantJson($sid)
    {
        $status = false;
        $message = Lang::get('redminportal::messages.error_delete_entry');
        
        $product = Product::find($sid);
        
        // No such id
        if ($product == null) {
            return json_encode(array('status' => $status, 'message' => $message));
        }
        
        // Check if there's any order related to this product
        if (count($product->orders) > 0) {
            $message = Lang::get('redminportal::messages.error_delete_product_already_ordered');
            return json_encode(array('status' => $status, 'message' => $message));
        }
        
        // Delete the product
        $result = $product->delete();
        
        if ($result) {
            $status = true;
            $message = Lang::get('redminportal::messages.success_delete_record');
        }

        return json_encode(array('status' => $status, 'message' => $message));
    }

    public function postStore()
    {
        $sid = Input::get('id');
        $product_id = Input::get('product_id');
        
        /*
         * Validate
         */
        $rules = array(
            'image'             => 'mimes:jpg,jpeg,png,gif|max:500',
            'name'              => 'required|unique:products,name' . (isset($sid) ? ',' . $sid : ''),
            'short_description' => 'required',
            'price'             => 'numeric',
            'sku'               => 'required|alpha_dash|unique:products,sku' . (isset($sid) ? ',' . $sid : ''),
            'category_id'       => 'required|numeric|min:1',
            'tags'              => 'regex:/^[a-z,0-9 -]+$/i',
            'weight'            => 'numeric',
            'length'            => 'numeric',
            'width'             => 'numeric',
            'height'            => 'numeric'
        );
        
        $messages = [
            'category_id.min' => 'The category field is required.'
        ];

        $validation = Validator::make(Input::all(), $rules, $messages);
        
        if ($validation->fails()) {
            $redirect_url = 'admin/products/create';
            
            if ($product_id and $sid) {
                $redirect_url = 'admin/products/edit-variant/' . $product_id . '/' . $sid;
            } elseif ($product_id and !$sid) {
                $redirect_url = 'admin/products/create-variant/' . $product_id;
            } elseif ($sid) {
                $redirect_url = 'admin/products/edit/' . $sid;
            }
            
            return redirect($redirect_url)->withErrors($validation)->withInput();
        }
        
        if ($product_id) {
            $parentProduct = Product::find($product_id);
            if (! $parentProduct) {
                $errors = new \Illuminate\Support\MessageBag;
                $errors->add('errorDeleteRecord', Lang::get('redminportal::messages.error_no_such_product'));
                return redirect('admin/products')->withErrors($errors);
            }
        }

        $name               = Input::get('name');
        $sku                = Input::get('sku');
        $price              = Input::get('price');
        $short_description  = Input::get('short_description');
        $long_description   = Input::get('long_description');
        $image              = Input::file('image');
        $featured           = (Input::get('featured') == '' ? false : true);
        $active             = (Input::get('active') == '' ? false : true);
        $category_id        = Input::get('category_id');
        $tags               = Input::get('tags');
        $weight_unit        = Input::get('weight_unit');
        $volume_unit        = Input::get('volume_unit');
        $weight             = Input::get('weight');
        $length             = Input::get('length');
        $width              = Input::get('width');
        $height             = Input::get('height');
        
        $product = (isset($sid) ? Product::find($sid) : new Product);

        if ($product == null) {
            $errors = new \Illuminate\Support\MessageBag;
            $errors->add('errorNoSuchProduct', Lang::get('redminportal::messages.error_no_such_product'));
            return redirect('/admin/products')->withErrors($errors);
        }

        $product->name = $name;
        $product->sku = $sku;
        $product->price = (isset($price) ? $price : 0);
        $product->short_description = $short_description;
        $product->long_description = $long_description;
        $product->featured = $featured;
        $product->active = $active;
        $product->category_id = $category_id;
        $product->weight_unit = $weight_unit;
        $product->volume_unit = $volume_unit;
        $product->weight = (($weight != '') ? $weight : null);
        $product->length = (($length != '') ? $length : null);
        $product->width  = (($width  != '') ? $width  : null);
        $product->height = (($height != '') ? $height : null);

        $product->save();

        // Save translations
        $translations = \Config::get('redminportal::translation');
        foreach ($translations as $translation) {
            $lang = $translation['lang'];
            if ($lang == 'en') {
                continue;
            }

            $translated_content = array(
                'name'                  => \Input::get($lang . '_name'),
                'short_description'     => \Input::get($lang . '_short_description'),
                'long_description'      => \Input::get($lang . '_long_description')
            );

            // Check if lang exist
            $translated_model = $product->translations->where('lang', $lang)->first();
            if ($translated_model == null) {
                $translated_model = new Translation;
            }

            $translated_model->lang = $lang;
            $translated_model->content = json_encode($translated_content);

            $product->translations()->save($translated_model);
        }

        if (! empty($tags)) {
            // Delete old tags
            $product->tags()->detach();

            // Save tags
            foreach (explode(',', $tags) as $tagName) {
                Tag::addTag($product, $tagName);
            }
        }

        if (Input::hasFile('image')) {
            //Upload the file
            $helper_image = new RImage;
            $filename = $helper_image->upload($image, 'products/' . $product->id, true);

            if ($filename) {
                // create photo
                $newimage = new Image;
                $newimage->path = $filename;

                // save photo to the loaded model
                $product->images()->save($newimage);
            }
        }
        
        // Link variant to parent Product
        if ($product_id) {
            // Only attach new variant
            if ($product_id and !$sid) {
                $parentProduct->variants()->attach($product->id);
            }
            return redirect('admin/products/view-variant/' . $product->id);
        }
        
        return redirect('admin/products');
    }

    public function getDelete($sid)
    {
        // Find the product using the user id
        $product = Product::find($sid);

        if ($product == null) {
            $errors = new \Illuminate\Support\MessageBag;
            $errors->add('errorDeleteRecord', Lang::get('redminportal::messages.error_delete_entry'));
            return redirect()->back()->withErrors($errors);
        }
        
        // Check if there's any order related to this product
        if (count($product->orders) > 0) {
            $errors = new \Illuminate\Support\MessageBag;
            $errors->add(
                'errorDeleteRecordAlreadyOrdered',
                Lang::get('redminportal::messages.error_delete_product_already_ordered')
            );
            return redirect()->back()->withErrors($errors);
        }
        
        // Check if there's any order related to this product's variants
        foreach ($product->variants as $variant) {
            if (count($variant->orders) > 0) {
                $errors = new \Illuminate\Support\MessageBag;
                $errors->add(
                    'errorDeleteRecordAlreadyOrdered',
                    Lang::get('redminportal::messages.error_delete_variant_already_ordered')
                );
                return redirect()->back()->withErrors($errors);
            }
        }
        
        // Delete the product
        $product->delete();

        return redirect()->back();
    }
    
    /*
     * Remove image from model
     * @param int Image ID
     * @return int Model ID if pass, null if fail
     */
    private function removeImage($sid)
    {
        $image = Image::find($sid);

        if ($image == null) {
            return null;
        }

        $model_id = $image->imageable_id;

        $image->delete();
        
        return $model_id;
    }
    
    public function getImgremove($sid)
    {
        $model_id = $this->removeImage($sid);

        if ($model_id == null) {
            $errors = new \Illuminate\Support\MessageBag;
            $errors->add('errorDeleteImage', Lang::get('redminportal::messages.error_delete_image'));
            return redirect('/admin/products')->withErrors($errors);
        }

        return redirect('admin/products/edit/' . $model_id);
    }
    
    public function getVariantImgremove($product_id, $sid)
    {
        $model_id = $this->removeImage($sid);

        if ($model_id == null) {
            $errors = new \Illuminate\Support\MessageBag;
            $errors->add('errorDeleteImage', Lang::get('redminportal::messages.error_delete_image'));
            return redirect('/admin/products/edit-variant/'. $product_id . '/' . $model_id)->withErrors($errors);
        }

        return redirect('admin/products/edit-variant/' . $product_id . '/' . $model_id);
    }
}
