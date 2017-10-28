<?php

namespace App\Http\Controllers;

use App\Film;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
use Webpatser\Countries\CountriesFacade;

class FilmController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Film::paginate(1);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50|unique:films,name',
            'description' => 'required',
            'rating' => 'required|int',
            'ticket_price' => 'required|numeric',
            'release_at' => 'required',
            'country' => 'required',
            'genre' => 'required',
            'photo' => 'required|mimes:jpeg,bmp,png',
        ]);

        $film = new Film();
        $film->name = $request->name;
        $film->description = $request->description;
        $film->rating = $request->rating;
        $film->ticket_price = $request->ticket_price;
        $film->release_at = Carbon::createFromFormat('d/m/Y', $request->release_at)->toDateTimeString();
        $film->country_id = $request->input('country');

        $photo = $request->file('photo');
        $request->photo->storeAs('public', $photo->getClientOriginalName());
        $film->photo = $photo->getClientOriginalName();

        $film->slug = str_slug($request->name);

        if ($film->save()) {
            $genders = $request->input('genre');
            if ($genders) {
                $genders = explode(',', $genders);
                $film->genres()->attach($genders);
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully created film!'
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'The film was not created '
        ], 401);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50|unique:films,name',
            'description' => 'required',
            'rating' => 'required',
            'ticket_price' => 'required',
            'release_at' => 'required',
            'country' => 'required',
            'genre' => 'required',
            'photo' => 'required|mimes:jpeg,bmp,png',
        ]);

        $film = Film::find($request->id);

        if (!$film) {
            return response()->json([
                'success' => false,
                'message' => 'Film not found'
            ], 404);
        }

        $film->name = $request->name;
        $film->description = $request->description;
        $film->rating = $request->rating;
        $film->ticket_price = $request->ticket_price;
        $film->release_at = Carbon::createFromFormat('d/m/Y', $request->release_at)->toDateTimeString();
        $film->country_id = $request->input('country');

        $photo = $request->file('photo');
        $request->photo->storeAs('public', $photo->getClientOriginalName());
        $film->photo = $photo->getClientOriginalName();

        $film->slug = str_slug($request->name);

        if ($film->save()) {
            $genders = $request->input('genre');
            if ($genders) {
                $genders = explode(',', $genders);
                $film->genres()->attach($genders);
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully created film!'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'The film was not created'
        ], 401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        if ($request->id) {
            $film = Film::find($request->id);
            if (!$film) {
                return response()->json([
                    'success' => false,
                    'message' => 'Film not found'
                ], 404);
            }
            Storage::delete($film->photo);

            $film->comments()->dissociate();
            $film->genres()->detach();
            $film->delete();

            return response()->json([
                'success' => true,
                'message' => 'Film deleted'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Film\'s identifier is required'
            ]);

        }
    }

    /**
     * Get user by Slug
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function slug(Request $request)
    {
        $slug = $request->slug;
        $film = Film::where('slug', $slug)->first();

        if (!$film) {
            return response()->json([
                'success' => false,
                'message' => 'The Film was not found by Slug'
            ], 404);
        }

        $releaseAt = (new Carbon($film->release_at));
        $country = CountriesFacade::find($film->country_id);
        $countryName = $country->name;
        $strGenders = '';
        foreach ($film->genres as $gender) {
            if ($strGenders) {
                $strGenders .= ', ';
            }
            $strGenders .= $gender->name;
        }

        return response()->json([
            'success' => true,
            'film' => $film,
            'comments' => $film->comments,
            'releaseAt' => $releaseAt->format('d/m/Y'),
            'countryName' => $countryName,
            'strGenders' => $strGenders,
        ], 200);
    }
}
