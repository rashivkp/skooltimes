<?php 
function getcls_id($invitedby){
    $typeresult=mysql_query("SELECT type FROM user WHERE usr_id=$_SESSION['invited_by']"); //geting the profession of invited person
                                      $row=mysql_fetch_array($typeresult);
                                      $invitedby_type=$row['type'];
                                     if($invitedby_type=="superior"){
                                          $clgresult=mysql_query("SELECT clg_id FROM college WHERE sup_id=$_SESSION['invited_by'] ");
                                          $row1=mysql_fetch_array($clgresult);
                                          $clg_id=$row1['clg_id'];
                                            
                                          $dptresult=mysql_query("SELECT dpt_id FROM department WHERE clg_id=$clg_id AND caption=$_POST['dpt'] "); 
                                          $row2=mysql_fetch_array($dptresult);
                                          $dpt_id=$row2['dpt_id'];
                                          
                                          if($clsresult=mysql_query("SELECT cls_id FROM class WHERE dpt_id=$dpt_id AND caption=$_POST['dpt'].$_POST['sem'] "))
                                            {
                                                $clscreate=mysql_query("INSERT INTO class (caption,dpt_id) VALUES($_POST['dpt'].$_POST['sem'], $dpt_id) ");
                                                $clsresult=mysql_query("SELECT cls_id FROM class WHERE caption=$_POST['dpt'].$_POST['sem'] AND dpt_id=$dpt_id "); 
                                            }
                                          $row=mysql_fetch_array($clsresult);
                                          $cls_id=$row2['cls_id'];
                                        
                                        return $cls_id;
                                        }
                                          
                                     else if($invitedby_type=="HOD"){                                               
                                            $dptresult=mysql_query("SELECT dpt_id FROM department WHERE dpt_hod_id=$_SESSION['invited_by'] "); //geting the department key by HOD id.
                                            $row2=mysql_fetch_array($dptresult);
                                            $dpt_id=$row2['dpt_id'];
                                            
                                            if($clsresult=mysql_query("SELECT cls_id FROM class WHERE dpt_id=$dpt_id AND caption=$_POST['dpt'].$_POST['sem'] "))
                                            {
                                                $clscreate=mysql_query("INSERT INTO class (caption,dpt_id) VALUES($_POST['dpt'].$_POST['sem'], $dpt_id) ");
                                                $clsresult=mysql_query("SELECT cls_id FROM class WHERE caption=$_POST['dpt'].$_POST['sem'] AND dpt_id=$dpt_id "); 
                                            }
                                          $row=mysql_fetch_array($clsresult);
                                          $cls_id=$row2['cls_id'];
                                        
                                        return $cls_id;
                                        }
                                        
                                        else if($invitedby_type=="tutor")
                                        {
                                                    $dptresult=mysql_query("SELECT dpt_id FROM tutor WHERE tut_id=$_SESSION['invited_by'] "); //geting the department key by HOD id.
                                                    $row2=mysql_fetch_array($dptresult);
                                                    $dpt_id=$row2['dpt_id'];
                                                    
                                                    if($clsresult=mysql_query("SELECT cls_id FROM class WHERE dpt_id=$dpt_id AND caption=$_POST['dpt'].$_POST['sem'] "))
                                                    {
                                                        $clscreate=mysql_query("INSERT INTO class (caption,dpt_id) VALUES($_POST['dpt'].$_POST['sem'], $dpt_id) ");
                                                        $clsresult=mysql_query("SELECT cls_id FROM class WHERE caption=$_POST['dpt'].$_POST['sem'] AND dpt_id=$dpt_id "); 
                                                    }
                                                  $row=mysql_fetch_array($clsresult);
                                                  $cls_id=$row2['cls_id'];
                                                
                                                return $cls_id;
                                    }
                                        else if($invitedby_type=="class tutor")
                                        {
                                                    $dptresult=mysql_query("SELECT dpt_id FROM tutor WHERE tut_id=$_SESSION['invited_by'] "); //geting the department key by HOD id.
                                                    $row2=mysql_fetch_array($dptresult);
                                                    $dpt_id=$row2['dpt_id'];
                                                    
                                                    if($clsresult=mysql_query("SELECT cls_id FROM class WHERE dpt_id=$dpt_id AND caption=$_POST['dpt'].$_POST['sem'] "))
                                                    {
                                                        $clscreate=mysql_query("INSERT INTO class (caption,dpt_id) VALUES($_POST['dpt'].$_POST['sem'], $dpt_id) ");
                                                        $clsresult=mysql_query("SELECT cls_id FROM class WHERE caption=$_POST['dpt'].$_POST['sem'] AND dpt_id=$dpt_id "); 
                                                    }
                                                  $row=mysql_fetch_array($clsresult);
                                                  $cls_id=$row2['cls_id'];
                                                
                                                return $cls_id;
                                        }
                                        else {
                                            $clsresult=mysql_query("SELECT cls_id FROM class WHERE rep_id=$_SESSION['invited_by'];
                                                 $row=mysql_fetch_array($clsresult);
                                                  $cls_id=$row2['cls_id'];

                                                return $cls_id;
                                                }

                                     
                                     
    }
?>