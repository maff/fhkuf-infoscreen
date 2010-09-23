<?php
class InfoScreen_Model_Soap
{
    /**
     * Get appointment list based on date and filters.
     *
     * @param  string $date
     * @param  array  $filters
     * @return array
     * @todo   use frontend code for filter generation
     */
    public function getDay($date = null, array $filters = array())
    {
        if(is_array($filters) && count($filters) > 0) {
            $filterArray = array();
            $allowed_params = array('course', 'lector', 'room');

            foreach($filters as $filter) {
                if(in_array($filter->key, $allowed_params)) {
                    $filterArray[] = new InfoScreen_Model_Filter($filter->key, $filter->value);
                }
            }
        }

        $model = new InfoScreen_Model_Day($date);
        $model->setFilters($filterArray);

        return $model->toArray();
    }

    /**
     * Get lists based on key.
     *
     * @param  string $key
     * @param  bool $selectfriendly
     * @return array
     */
    public function getList($key, $selectfriendly = true)
    {
        if(!in_array($key, array('course', 'lector', 'room'))) {
            return array();
        }

        $model = InfoScreen_Model_List::getInstance($key);

        if($selectfriendly) {
            return $model->getSelectData();
        }

        return $model->getData();
    }
}