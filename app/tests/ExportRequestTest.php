<?php

class ExportRequestTest extends TestCase
{
    /**
     * @test
     *
     * @return [type] [description]
     */
    public function name_validation()
    {
        $input     = array('event' => 'delivered');
        $repo      = new ExportRequestRepository;

        try {
            $request = $repo->store($input);
        } catch (Exception $e) {
        }

        assertFalse(isset($request));

        assertFalse($repo->validator->passes());

        assertEquals($repo->validator->errors()->first('name'), 'The name field is required.');
    }

    /**
     * @test
     *
     * @return [type] [description]
     */
   public function event_type_validation()
   {
       $input     = array('event' => 'random');
       $repo      = new ExportRequestRepository;

       try {
           $request = $repo->store($input);
       } catch (Exception $e) {
       }

       assertFalse(isset($request));

       assertFalse($repo->validator->passes());

       assertEquals($repo->validator->errors()->first('event'), 'The selected event is invalid.');
   }

   /**
    * @test
    *
    * @return [type] [description]
    */
  public function valid_request()
  {
      $input     = array('event' => '', 'name' => 'New Request');
      $repo      = new ExportRequestRepository;

      try {
          $request = $repo->store($input);
      } catch (Exception $e) {
      }

      assertTrue(isset($request));

      assertTrue($repo->validator->passes());

      assertEquals($request->id, 1);
  }
}
