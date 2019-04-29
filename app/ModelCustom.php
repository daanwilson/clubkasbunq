<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

trait ModelCustom {

    static $cache = array();
    static $filtering = true;

    static function get(array $attributes = []) {
        return new static($attributes);
    }

    static function allCached() {
        if (!array_key_exists('all', static::$cache)) {
            static::$cache['all'] = static::all();
        }
        return static::$cache['all'];
    }

    static function allCachedByKey($key,$columns = ['*']) {
        if (!array_key_exists('allKey_' . $key, static::$cache)) {
            static::$cache['allKey_' . $key] = Custom::setKey(static::all($columns), $key);
        }
        return static::$cache['allKey_' . $key];
    }

    static function getIdByField($name, $fieldname) {
        $name = trim($name);
        if ($name != '') {
            $objects = static::allCachedByKey($fieldname);
            if (array_key_exists($name, $objects)) {
                return $objects[$name]->id;
            }
            $object = new static();
            $object->$fieldname = $name;
            $object->save();
            static::$cache = array();
            return $object->id;
        }
    }

    function getTableConfig() {
        return [];
    }

    static function filtered(Request $request,$defaults=[]) {
        return static::appendQuery((new static)->newQuery(), $request, $defaults);
    }

    /**
     * Get a new query builder for the model's table.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    static function appendQuery(Builder $builder, Request $request) {
        $table = (new static)->getTableConfig();
        if ($request->q) { //search query
            $builder = static::addSearch($builder, $request->q);
        }
        if ($request->f) { //filter query
            $builder = static::addFilters($builder, $request->f);
        }
        if ($request->o) { //order by
            $builder = static::addSorting($builder, $request->o);
        }
        if (array_key_exists('order', $table) && !empty($table['order'])) {
            $builder = static::addSorting($builder, $table['order']);          
        }
        
        return $builder;
    }

    protected static function addSearch(Builder $builder, string $query) {
        if ($query != '') {
            $table = (new static)->getTableConfig();
            if (array_key_exists('search', $table) && is_array($table['search'])) {
                //$query = explode(",",$query);
                $query = preg_split("/[\s,-]+/", $query);
                foreach ($query as $q) {
                    if ($q != '') {
                        $builder->where(function($builder) use ($table, $q) {
                            foreach ($table['search'] as $column => $values) {
                                $builder->orWhere($column, $values['operator'], sprintf($values['value'], $q));
                            }
                        });
                    }
                }
            }
        }
        return $builder;
    }

    protected static function addFilters(Builder $builder, string $query) {
        if ($query != '') {
            $filtersArr = static::extractFilters($query);
            $filtersArr = static::addDefaultFilters($filtersArr);
            foreach ($filtersArr as $column=>$values) {
                if (!empty($values)) {
                    if (Schema::hasColumn((new static)->getTable(), $column)) {
                        $builder->Where(function($query) use ($column,$values){
                                $query->WhereIn($column, $values);
                                if(in_array("null",$values) || in_array(null,$values)){
                                    $query->orWhere($column, null);
                                }
                        });
                    } else {
                        $builder->whereHas($column, function ($query) use ($values, $column) {
                            $query->whereIn((new static)->$column()->getRelatedPivotKeyName(), $values);
                        });
                    }
                }
            }
        }
        return $builder;
    }
    static function extractFilters($query){
        $filters = array_filter(explode("|", $query));
        $filtersArr = [];
        foreach ($filters as $filter) {
            list($column, $values) = explode(":", $filter);
            $filtersArr[$column] = explode(",",$values);
        }
        return $filtersArr;
    }
    protected static function addDefaultFilters($filtersArr){
        $table = (new static)->getTableConfig();
        if(array_key_exists('filter',$table) && is_array($table['filter'])){
            foreach($table['filter'] as $column=>$values){
                if(array_key_exists($column, $filtersArr)){
                    $filtersArr[$column] = array_merge($filtersArr[$column],$values);
                }else{
                    $filtersArr[$column] = $values;
                }
            }
        }
        return $filtersArr;
    }

    protected static function addSorting(Builder $builder, string $sorting) {
        $sorting = explode(",", $sorting);
        foreach ($sorting as $sort) {
            if ($sort != '') {
                $parts = explode("|", $sort);
                $builder->orderBy($parts[0], $parts[1]);
            }
        }
        return $builder;
    }
}
