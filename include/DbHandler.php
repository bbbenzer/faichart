<?php

/**
 * Class to handle all db operations
 * This class will have CRUD methods for database tables
 *
 * @author Ravi Tamada
 * @link URL Tutorial link
 */
class DbHandler {

    private $conn;

    function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
    /**
     * Checking user login
     * @param String $email User login email id
     * @param String $password User login password
     * @return boolean User login status success/fail
     */
    public function checkLogin($user_name, $user_password_check) {
        // fetching user by username
        $stmt = $this->conn->prepare("SELECT user_password FROM tl_user WHERE user_name = ?");

        $stmt->bind_param("s", $user_name);

        $stmt->execute();

        $stmt->bind_result($user_password);

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Found user with the email
            // Now verify the password

            $stmt->fetch();

            $stmt->close();

			if($user_password == $user_password_check){
				// User password is correct
				return TRUE;
			}else{
     			// user password is incorrect
 				return FALSE;
			}

        } else {
            $stmt->close();

            // user not existed with the email
            return FALSE;
        }
    }

    /**
     * Checking for duplicate user by email address
     * @param String $email email to check in db
     * @return boolean
     */
    private function isUserExists($email) {
        $stmt = $this->conn->prepare("SELECT Username from users WHERE  Login_Code = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    /**
     * Fetching user by email
     * @param String $email User email id
     */
    public function getUserByUsername($user_name,$user_password) {
		
        $stmt = $this->conn->prepare( "SELECT Username,`Password`,CONCAT(FName,' ',LName) AS DName FROM login WHERE Username = ? AND Password = ?" );
        $stmt->bind_param("ss", $user_name,$user_password);
    
        if ($stmt->execute()) {
            $stmt->bind_result($user_name,$user_password,$user_displayname);
            $stmt->fetch();
            $user = array();
            $user["user_name"] = $user_name;
            $user["user_password"] = $user_password;
            $user["user_displayname"] = $user_displayname;
            $stmt->close();
            return $user;
        } else {
            return NULL;
        }
    }
    
    public function InsertPlan($DocNo, $RefDocNo, $CusName, $DocDate, $Percent, $Dep_Code, $Priority){
        $stmt = $this->conn->prepare("INSERT INTO veva.product_planlist(DocNo, RefDocNo, CusName, DocDate, Percent, Dep_code, Priority) values(?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiii", $DocNo, $RefDocNo, $CusName, $DocDate, $Percent, $Dep_Code, $Priority);
        $result = $stmt->execute();
        
        if (false === $result) {
            die('execute() failed: ' . htmlspecialchars($stmt->error));
        }
        $stmt->close();
        return $result;
    }
    
    public function isDocNoExists($DocNo){
        $stmt = $this->conn->prepare("SELECT DocNo from veva.product_planlist WHERE  DocNo = ?");
        $stmt->bind_param("s", $DocNo);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    public function getPlansList_start(){
        $stmt = $this->conn->prepare("SELECT a.DocNo,a.DocDate,a.RefDocno,a.Cus_Code,a.Detail ,b.FName, a.Priority FROM veva.product_plan a left join veva.customer b ON a.Cus_Code = b.Cus_Code Where a.IsCancel = 0 and a.x_Status = 1 and a.isStart = 0 order by a.RefDocno DESC");
        //$stmt->bind_param("i", $user_department_code);
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
    }
    
    public function getPlansList($user_department_code){
		
		//====================
		$sql = "user_department_code = $user_department_code";
		$stmt = $this->conn->prepare("INSERT INTO veva.logx ( logx ) values( ? )");
		$stmt->bind_param("s",$sql);
		$stmt->execute();
		$stmt->close();
 		//==================== 
		if($user_department_code == 9){
			$stmt = $this->conn->prepare("SELECT DocNo,RefDocNo,CusName,DocDate,Percent,Dep_Code,Priority,d1,d2,d3,d4,d5,d6,d7,d8,d9,d10,d11 FROM veva.product_planlist WHERE status = '1'  AND d9 = 100 Order by Priority DESC,DocDate DESC");
		}else{
        	$stmt = $this->conn->prepare("SELECT DocNo,RefDocNo,CusName,DocDate,Percent,Dep_Code,Priority,d1,d2,d3,d4,d5,d6,d7,d8,d9,d10,d11 FROM veva.product_planlist WHERE status = '1' Order by Priority DESC,DocDate DESC");
		}

//        $stmt->bind_param("i", $user_department_code);
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
    }
    
    public function getListDetail($DocNo) {
        $stmt = $this->conn->prepare("SELECT b.Id,a.DocNo,f.RefDocNo,c.Item_Code,e.imgPath,c.Barcode as Name1,e.Barcode as Name2,b.Qty ,d.Unit_Name as UnitName ,g.FName as CusName,b.xSize ,b.priority from veva.product_plan_detail_sub a left join veva.product_plan_detail b on a.Item_Code = b.Item_Code and b.DocNo = ? left join veva.item c on a.Item_Code_Sub  = c.Item_Code left join veva.item_unit d on c.Unit_Code = d.Unit_Code left join veva.item e on a.Item_Code  = e.Item_Code left join veva.product_plan f on a.DocNo  = f.DocNo left join veva.customer g on f.Cus_Code  = g.Cus_Code where a.DocNo = ? and a.Item_Code_Sub like '04%'");
        $stmt->bind_param("ss",$DocNo,$DocNo);
        
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
        
    }
    
    public function isItemExists($DocNo,$Item_Code){
        $stmt = $this->conn->prepare("SELECT DocNo,Item_Code from veva.product_planitem WHERE  DocNo = ? AND Item_Code = ?");
        $stmt->bind_param("ss", $DocNo,$Item_Code);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
    
    public function InsertItem($DocNo, $RefDocNo, $Item_Code, $imgPath, $Name1, $Name2, $Qty, $UnitName, $CusName, $xSize, $priority){
        $stmt = $this->conn->prepare("INSERT INTO veva.product_planitem(DocNo, RefDocNo, Item_Code, imgPath, Name1, Name2, Qty, UnitName, CusName, xSize, priority) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssisssi", $DocNo, $RefDocNo, $Item_Code, $imgPath, $Name1, $Name2, $Qty, $UnitName, $CusName, $xSize, $priority);
        $result = $stmt->execute();
        
        if (false === $result) {
            die('execute() failed: ' . htmlspecialchars($stmt->error));
        }
        $stmt->close();
        return $result;
    }
    
    public function getListDetail_Item($DocNo,$user_department_code1,$user_department_code2) {
        if($user_department_code2 == 1){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11,a.StepUp FROM veva.product_planitem a left join veva.product_planitemdetail b on a.Item_Code = b.Item_Code Where a.DocNo = ? and a.d1 <= 101 and b.step like ? group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }else if($user_department_code2 == 2){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11,a.StepUp FROM veva.product_planitem a left join veva.product_planitemdetail b on a.Item_Code = b.Item_Code Where a.DocNo = ? and a.d2 <= 101 and b.step like ? group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }if($user_department_code2 == 3){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11,a.StepUp FROM veva.product_planitem a left join veva.product_planitemdetail b on a.Item_Code = b.Item_Code Where a.DocNo = ? and a.d3 <= 101 and b.step like ? group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }if($user_department_code2 == 4){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11,a.StepUp FROM veva.product_planitem a left join veva.product_planitemdetail b on a.Item_Code = b.Item_Code Where a.DocNo = ? and a.d4 <= 101 and b.step like ? group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }if($user_department_code2 == 5){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11,a.StepUp FROM veva.product_planitem a left join veva.product_planitemdetail b on a.Item_Code = b.Item_Code Where a.DocNo = ? and a.d5 <= 101 and b.step like ? group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }if($user_department_code2 == 6){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11,a.StepUp FROM veva.product_planitem a left join veva.product_planitemdetail b on a.Item_Code = b.Item_Code Where a.DocNo = ? and a.d6 <= 101 and b.step like ? group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }if($user_department_code2 == 7){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11,a.StepUp FROM veva.product_planitem a left join veva.product_planitemdetail b on a.Item_Code = b.Item_Code Where a.DocNo = ? and a.d7 <= 101 and b.step like ? group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }if($user_department_code2 == 8){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11,a.StepUp FROM veva.product_planitem a left join veva.product_planitemdetail b on a.Item_Code = b.Item_Code Where a.DocNo = ? and a.d8 <= 101 and b.step like ? group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }if($user_department_code2 == 9){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11,a.StepUp FROM veva.product_planitem a left join veva.product_planitemdetail b on a.Item_Code = b.Item_Code Where a.DocNo = ? and a.d9 <= 101 and b.step like ? group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }if($user_department_code2 == 10){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11,a.StepUp FROM veva.product_planitem a left join veva.product_planitemdetail b on a.Item_Code = b.Item_Code Where a.DocNo = ? and a.d10 <= 101 and b.step like ? group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }if($user_department_code2 == 11){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11,a.StepUp FROM veva.product_planitem a left join veva.product_planitemdetail b on a.Item_Code = b.Item_Code Where a.DocNo = ? and a.d11 <= 101 and b.step like ? group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }
        
        $stmt->bind_param("ss",$DocNo,$user_department_code1);
        
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
        
    }
    
    public function isIteminlistExists($DocNo,$user_department_code1){
        if($user_department_code1 == 1){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11 FROM veva.product_planitem a left join veva.product_planitemdetail b on a.DocNo = b.DocNo Where a.DocNo = ? and a.d1 <= 100 and b.step like '%1.%' group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }else if($user_department_code1 == 2){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11 FROM veva.product_planitem a left join veva.product_planitemdetail b on a.DocNo = b.DocNo Where a.DocNo = ? and a.d2 <= 100 and b.step like '%2.%' group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }if($user_department_code1 == 3){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11 FROM veva.product_planitem a left join veva.product_planitemdetail b on a.DocNo = b.DocNo Where a.DocNo = ? and a.d3 <= 100 and b.step like '%3.%' group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }if($user_department_code1 == 4){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11 FROM veva.product_planitem a left join veva.product_planitemdetail b on a.DocNo = b.DocNo Where a.DocNo = ? and a.d4 <= 100 and b.step like '%4.%' group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }if($user_department_code1 == 5){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11 FROM veva.product_planitem a left join veva.product_planitemdetail b on a.DocNo = b.DocNo Where a.DocNo = ? and a.d5 <= 100 and b.step like '%5.%' group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }if($user_department_code1 == 6){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11 FROM veva.product_planitem a left join veva.product_planitemdetail b on a.DocNo = b.DocNo Where a.DocNo = ? and a.d6 <= 100 and b.step like '%6.%' group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }if($user_department_code1 == 7){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11 FROM veva.product_planitem a left join veva.product_planitemdetail b on a.DocNo = b.DocNo Where a.DocNo = ? and a.d7 <= 100 and b.step like '%7.%' group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }if($user_department_code1 == 8){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11 FROM veva.product_planitem a left join veva.product_planitemdetail b on a.DocNo = b.DocNo Where a.DocNo = ? and a.d8 <= 100 and b.step like '%8.%' group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }if($user_department_code1 == 9){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11 FROM veva.product_planitem a left join veva.product_planitemdetail b on a.DocNo = b.DocNo Where a.DocNo = ? and a.d9 <= 100 and b.step like '%9.%' group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }if($user_department_code1 == 10){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11 FROM veva.product_planitem a left join veva.product_planitemdetail b on a.DocNo = b.DocNo Where a.DocNo = ? and a.d10 <= 100 and b.step like '%10.%' group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }if($user_department_code1 == 11){
            $stmt = $this->conn->prepare("SELECT a.DocNo as Id,a.imgPath,a.Item_Code,a.Name1 as Barcode,a.Qty,a.UnitName as Unit_Name,a.xSize,a.priority,a.Percent,a.d1,a.d2,a.d3,a.d4,a.d5,a.d6,a.d7,a.d8,a.d9,a.d10,a.d11 FROM veva.product_planitem a left join veva.product_planitemdetail b on a.DocNo = b.DocNo Where a.DocNo = ? and a.d11 <= 100 and b.step like ? group by Item_Code Order by a.priority DESC,a.Name1 ASC ");
        }
        $stmt->bind_param("s",$DocNo);
        
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
    
    public function getStep($DocNo) {
        $stmt = $this->conn->prepare("SELECT a.Id,i.FName as cus_name,f.DocNo,f.RefDocNo,c.Item_Code,c.Barcode as name1,e.Barcode as name2,a.Qty,d.Unit_Name,e.imgPath,a.xSize ,h.NameTH as Step,a.priority FROM veva.product_plan_detail a left join veva.product_plan_detail_sub b on a.Item_Code = b.Item_Code and b.Item_Code_Sub like '04%' and a.DocNo = b.DocNo left join veva.item c on b.Item_Code_Sub  = c.Item_Code left join veva.item_unit d on c.Unit_Code = d.Unit_Code left join veva.item e on a.Item_Code  = e.Item_Code left join veva.product_plan f on a.DocNo  = f.DocNo right JOIN veva.item_detail g on a.Item_Code = g.Item_Code AND g.Item_Code_Sub LIKE '10%'  right join veva.item h on g.Item_Code_Sub = h.Item_Code  left JOIN veva.customer i on f.Cus_Code = i.Cus_Code where a.DocNo = ? ");
        $stmt->bind_param("s",$DocNo);
        
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
        
    }
    
    
    public function isStepExists($DocNo,$Item_Code,$Step){
        $stmt = $this->conn->prepare("SELECT DocNo,Item_Code,Step from veva.product_planitemdetail WHERE  DocNo = ? AND Item_Code = ? AND Step = ?");
        $stmt->bind_param("sss", $DocNo,$Item_Code,$Step);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
    
    public function InsertStep($DocNo,$Item_Code, $Step, $Qty, $UnitName){
        $stmt = $this->conn->prepare("INSERT INTO veva.product_planitemdetail(DocNo, Item_Code, Step, Qty, Unit) values(?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $DocNo, $Item_Code, $Step, $Qty, $UnitName);
        $result = $stmt->execute();
        
        if (false === $result) {
            die('execute() failed: ' . htmlspecialchars($stmt->error));
        }
        $stmt->close();
        return $result;
    }
    
    public function get_Item($DocNo,$Item_Code) {
        $stmt = $this->conn->prepare("SELECT PlanItemId as Id,CusName as cus_name,DocNo,RefDocNo,Item_Code,Name1 as name1,Name2 as name2,Qty,UnitName as Unit_Name,imgPath,xSize,Cast,Silver,W1,W2,W3,W4,Percent,priority FROM veva.product_planitem Where DocNo = ? AND Item_Code = ? ");
        $stmt->bind_param("ss",$DocNo,$Item_Code);
        
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
        
    }
    
    public function get_ItemStep($DocNo,$Item_Code,$user_department_code) {
        $stmt = $this->conn->prepare("SELECT PlanItemdetailId,DocNo,Item_Code,Step,Qty,ProcessQty,Unit,Percent FROM veva.product_planitemdetail Where DocNo = ? and Item_Code = ? and Step like ? Order by Step ASC");
        $stmt->bind_param("sss",$DocNo,$Item_Code,$user_department_code);
        
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
        
    }
    
    public function updateItem($Cast,$Silver,$W1,$W2,$W3,$W4,$Percent,$PlanItemId) {
        $stmt = $this->conn->prepare("UPDATE veva.product_planitem set Cast = ?, Silver = ?, W1 = ?, W2 = ?, W3=?, W4=?, Percent=? WHERE PlanItemId = ?");
        $stmt->bind_param("ssssssii", $Cast,$Silver,$W1,$W2,$W3,$W4,$Percent,$PlanItemId);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }
    
	// Update 15/7/59
	
	    public function getStepItem($Item_Code) {
        
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
        
    }
	
	public function updateItemStepUp($DocNo,$Item_Code,$StepUp) {
		$stmt = $this->conn->prepare("SELECT PlanItemId FROM veva.product_planitem Where DocNo = ? and Item_Code = ?");
		$stmt->bind_param("ss",$DocNo,$Item_Code);
        $stmt->execute();
        $stmt->bind_result($PlanItemId);
        $stmt->store_result();
        $stmt->fetch();
        $stmt->close();

 
		$tmp = array();
		$n=1;	
		$stmt = $this->conn->prepare("SELECT SUBSTR(product_planitemdetail.Step,1,1) AS Cnt FROM product_planitemdetail WHERE product_planitemdetail.DocNo = ? AND ChkStep = 1 AND Item_Code = ? GROUP BY Cnt ORDER BY Cnt ASC");
	
	    $stmt->bind_param("ss",$DocNo,$Item_Code);
		$stmt->execute();
		$stmt->bind_result($Cnt);
			while ($stmt->fetch()) {
						$tmp[$n] = $Cnt;
						$n++;
						
			}
        $stmt->close();

			for($i=1; $i<$n;$i++){			
					if( $StepUp == $tmp[$i]  ){
						$StepUp =  $tmp[$i+1];
						break;
					}
			}
	  //====================
		$sql = "UPDATE veva.product_planitem set StepUp = $StepUp WHERE PlanItemId = $PlanItemId";
	    $stmt = $this->conn->prepare("INSERT INTO veva.logx ( logx ) values( ? )");
        $stmt->bind_param("s", $sql);
        $stmt->execute();
	    $stmt->close();		
 		//==================== 
        $stmt = $this->conn->prepare("UPDATE veva.product_planitem set StepUp = ? WHERE PlanItemId = ?");
        $stmt->bind_param("si", $StepUp,$PlanItemId);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
		if($StepUp == 9){
			$stmt = $this->conn->prepare("UPDATE veva.product_planlist set d9 = 100 WHERE DocNo = ?");
			$stmt->bind_param("s", $DocNo);
			$stmt->execute();
			$num_affected_rows = $stmt->affected_rows;
			$stmt->close();
		}
		
        return  $StepUp;
    }
	
    public function InsertTech($PlanItemdetailId,$tech_code,$tech_name,$qty,$user_code){
        $stmt = $this->conn->prepare("INSERT INTO veva.product_planitemdetailprocess(PlanItemdetailId, tech_code, tech_name, qty,user_code,datetime) values(?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssii",$PlanItemdetailId,$tech_code,$tech_name,$qty,$user_code);
        $result = $stmt->execute();
        
        if (false === $result) {
            die('execute() failed: ' . htmlspecialchars($stmt->error));
        }else{
//            $stmt1 = $this->conn->prepare("UPDATE veva.product_planitemdetail set ProcessQty = ?, Percent=? WHERE PlanItemdetailId = ?");
//            $stmt1->bind_param("iis", $ProcessQty,$Percent,$PlanItemdetailId);
        }
        $stmt->close();
        return $result;
    }
    
    public function DeleteTech($reason,$PlanItemdetailId) {
        $stmt = $this->conn->prepare("UPDATE veva.product_planitemdetailprocess set reason = ?, isactive = 1 WHERE PlanItemId = ?");
        $stmt->bind_param("ss", $reason,$PlanItemdetailId);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }
    
    public function Update_Percent($PlanItemdetailId,$user_department_code) {
        $stmt = $this->conn->prepare("SELECT SUM(qty) as sum FROM veva.product_planitemdetailprocess where isactive = 1 and PlanItemdetailId = ?");
        $stmt->bind_param("s", $PlanItemdetailId);
        $stmt->execute();
        $stmt->bind_result($sum);
        $stmt->store_result();
        $stmt->fetch();
        $stmt->close();
        
        $stmt2 = $this->conn->prepare("UPDATE veva.product_planitemdetail set ProcessQty = ? WHERE PlanItemdetailId = ?");
        $stmt2->bind_param("is", $sum,$PlanItemdetailId);
        $stmt2->execute();
        $num_affected_rows = $stmt2->affected_rows;
        $stmt2->close();
        
        if($num_affected_rows > 0){
            $stmt3 = $this->conn->prepare("SELECT Qty,ProcessQty,Percent,DocNo,Item_Code  FROM veva.product_planitemdetail where PlanItemdetailId = ?");
            $stmt3->bind_param("s", $PlanItemdetailId);
            $stmt3->execute();
            $stmt3->bind_result($t_Qty,$t_ProcessQty,$t_Percent,$t_Doc_No,$t_Item_Code);
            $stmt3->store_result();
            $stmt3->fetch();
            $stmt3->close();
            
            if($t_ProcessQty > $t_Qty){
                $t_Percent = 100;
            }else{
                $t_Percent = ($t_ProcessQty/$t_Qty)*100;
            }
            
            $stmt4 = $this->conn->prepare("UPDATE veva.product_planitemdetail set Percent = ? WHERE PlanItemdetailId = ?");
            $stmt4->bind_param("is", $t_Percent,$PlanItemdetailId);
            $stmt4->execute();
            $num_affected_rows2 = $stmt4->affected_rows;
            $stmt4->close();
            
            if($num_affected_rows2 > 0){
                $stmt5 = $this->conn->prepare("SELECT Percent FROM veva.product_planitemdetail WHERE DocNo = ? and Item_Code = ? and Step like ?");
                $stmt5->bind_param("sss", $t_Doc_No,$t_Item_Code,$user_department_code);
                $stmt5->execute();
                $stmt5->bind_result($m_sumpercent);
                $stmt5->store_result();
                $stmt5->fetch();
                $stmt5->close();
                
                $item_percent = $m_sumpercent;
				
                if($user_department_code == '1%'){
                    $stmt10 = $this->conn->prepare("UPDATE veva.product_planitem set d1 = ? WHERE DocNo = ? and Item_Code = ?");
                }elseif($user_department_code == '2%'){
                    $stmt10 = $this->conn->prepare("UPDATE veva.product_planitem set d2 = ? WHERE DocNo = ? and Item_Code = ?");
                }elseif($user_department_code == '3%'){
                    $stmt10 = $this->conn->prepare("UPDATE veva.product_planitem set d3 = ? WHERE DocNo = ? and Item_Code = ?");
                }elseif($user_department_code == '4%'){
                    $stmt10 = $this->conn->prepare("UPDATE veva.product_planitem set d4 = ? WHERE DocNo = ? and Item_Code = ?");
                }elseif($user_department_code == '5%'){
                    $stmt10 = $this->conn->prepare("UPDATE veva.product_planitem set d5 = ? WHERE DocNo = ? and Item_Code = ?");
                }elseif($user_department_code == '6%'){
                    $stmt10 = $this->conn->prepare("UPDATE veva.product_planitem set d6 = ? WHERE DocNo = ? and Item_Code = ?");
                }elseif($user_department_code == '7%'){
                    $stmt10 = $this->conn->prepare("UPDATE veva.product_planitem set d7 = ? WHERE DocNo = ? and Item_Code = ?");
                }elseif($user_department_code == '8%'){
                    $stmt10 = $this->conn->prepare("UPDATE veva.product_planitem set d8 = ? WHERE DocNo = ? and Item_Code = ?");
                }elseif($user_department_code == '9%'){
                    $stmt10 = $this->conn->prepare("UPDATE veva.product_planitem set d9 = ? WHERE DocNo = ? and Item_Code = ?");
                }elseif($user_department_code == '10%'){
                    $stmt10 = $this->conn->prepare("UPDATE veva.product_planitem set d10 = ? WHERE DocNo = ? and Item_Code = ?");
                }elseif($user_department_code == '11%'){
                    $stmt10 = $this->conn->prepare("UPDATE veva.product_planitem set d11 = ? WHERE DocNo = ? and Item_Code = ?");
                }
                $stmt10->bind_param("iss",$item_percent,$t_Doc_No,$t_Item_Code);
                $stmt10->execute();
                $num_affected_rows3 = $stmt10->affected_rows;
                $stmt10->close();
            }
			
            if($num_affected_rows3 > 0){
                if($user_department_code == '1%'){
                    $user_department_code2 = '1';
                    $stmt6 = $this->conn->prepare("SELECT count(*),SUM(d1) FROM veva.product_planitem where  DocNo = ? ");
                    $stmt6->bind_param("s", $t_Doc_No);
                    $stmt6->execute();
                    $stmt6->bind_result($p_row,$p_sumpercent);
                    $stmt6->store_result();
                    $stmt6->fetch();
                    $stmt6->close();
                    
                    $plan_Percent = ($p_sumpercent/$p_row);
                    
                    $stmt7 = $this->conn->prepare("UPDATE veva.product_planlist set d1 = ? WHERE DocNo = ? ");
                    $stmt7->bind_param("is", $plan_Percent,$t_Doc_No);
                    $stmt7->execute();
                    $num_affected_rows4 = $stmt7->affected_rows;
                    $stmt7->close();
                }elseif($user_department_code == '2%'){
                    $user_department_code2 = '2';
                    $stmt6 = $this->conn->prepare("SELECT count(*),SUM(d2) FROM veva.product_planitem where  DocNo = ? ");
                    $stmt6->bind_param("s", $t_Doc_No);
                    $stmt6->execute();
                    $stmt6->bind_result($p_row,$p_sumpercent);
                    $stmt6->store_result();
                    $stmt6->fetch();
                    $stmt6->close();
                    $plan_Percent = ($p_sumpercent/$p_row);
                    $stmt7 = $this->conn->prepare("UPDATE veva.product_planlist set d2 = ? WHERE DocNo = ? ");
                    $stmt7->bind_param("is", $plan_Percent,$t_Doc_No);
                    $stmt7->execute();
                    $num_affected_rows4 = $stmt7->affected_rows;
                    $stmt7->close();
                }elseif($user_department_code == '3%'){
                    $user_department_code2 = '3';
                    $stmt6 = $this->conn->prepare("SELECT count(*),SUM(d3) FROM veva.product_planitem where  DocNo = ? ");
                    $stmt6->bind_param("s", $t_Doc_No);
                    $stmt6->execute();
                    $stmt6->bind_result($p_row,$p_sumpercent);
                    $stmt6->store_result();
                    $stmt6->fetch();
                    $stmt6->close();
                    $plan_Percent = ($p_sumpercent/$p_row);
                    $stmt7 = $this->conn->prepare("UPDATE veva.product_planlist set d3 = ? WHERE DocNo = ? ");
                    $stmt7->bind_param("is", $plan_Percent,$t_Doc_No);
                    $stmt7->execute();
                    $num_affected_rows4 = $stmt7->affected_rows;
                    $stmt7->close();

                }elseif($user_department_code == '4%'){
                    $user_department_code2 = '4';
                    $stmt6 = $this->conn->prepare("SELECT count(*),SUM(d4) FROM veva.product_planitem where  DocNo = ? ");
                    $stmt6->bind_param("s", $t_Doc_No);
                    $stmt6->execute();
                    $stmt6->bind_result($p_row,$p_sumpercent);
                    $stmt6->store_result();
                    $stmt6->fetch();
                    $stmt6->close();
                    $plan_Percent = ($p_sumpercent/$p_row);
                    $stmt7 = $this->conn->prepare("UPDATE veva.product_planlist set d4 = ? WHERE DocNo = ? ");
                    $stmt7->bind_param("is", $plan_Percent,$t_Doc_No);
                    $stmt7->execute();
                    $num_affected_rows4 = $stmt7->affected_rows;
                    $stmt7->close();

                }elseif($user_department_code == '5%'){
                    $user_department_code2 = '5';
                    $stmt6 = $this->conn->prepare("SELECT count(*),SUM(d5) FROM veva.product_planitem where  DocNo = ? ");
                    $stmt6->bind_param("s", $t_Doc_No);
                    $stmt6->execute();
                    $stmt6->bind_result($p_row,$p_sumpercent);
                    $stmt6->store_result();
                    $stmt6->fetch();
                    $stmt6->close();
                    $plan_Percent = ($p_sumpercent/$p_row);
                    $stmt7 = $this->conn->prepare("UPDATE veva.product_planlist set d5 = ? WHERE DocNo = ? ");
                    $stmt7->bind_param("is", $plan_Percent,$t_Doc_No);
                    $stmt7->execute();
                    $num_affected_rows4 = $stmt7->affected_rows;
                    $stmt7->close();

                }elseif($user_department_code == '6%'){
                    $user_department_code2 = '6';
                    $stmt6 = $this->conn->prepare("SELECT count(*),SUM(d6) FROM veva.product_planitem where  DocNo = ?");
                    $stmt6->bind_param("s", $t_Doc_No);
                    $stmt6->execute();
                    $stmt6->bind_result($p_row,$p_sumpercent);
                    $stmt6->store_result();
                    $stmt6->fetch();
                    $stmt6->close();
                    $plan_Percent = ($p_sumpercent/$p_row);
                    $stmt7 = $this->conn->prepare("UPDATE veva.product_planlist set d6 = ? WHERE DocNo = ? ");
                    $stmt7->bind_param("is", $plan_Percent,$t_Doc_No);
                    $stmt7->execute();
                    $num_affected_rows4 = $stmt7->affected_rows;
                    $stmt7->close();

                }elseif($user_department_code == '7%'){
                    $user_department_code2 = '7';
                    $stmt6 = $this->conn->prepare("SELECT count(*),SUM(d7) FROM veva.product_planitem where  DocNo = ?");
                    $stmt6->bind_param("s", $t_Doc_No);
                    $stmt6->execute();
                    $stmt6->bind_result($p_row,$p_sumpercent);
                    $stmt6->store_result();
                    $stmt6->fetch();
                    $stmt6->close();
                    $plan_Percent = ($p_sumpercent/$p_row);
                    $stmt7 = $this->conn->prepare("UPDATE veva.product_planlist set d7 = ? WHERE DocNo = ? ");
                    $stmt7->bind_param("is", $plan_Percent,$t_Doc_No);
                    $stmt7->execute();
                    $num_affected_rows4 = $stmt7->affected_rows;
                    $stmt7->close();

                }elseif($user_department_code == '8%'){
                    $user_department_code2 = '8';
                    $stmt6 = $this->conn->prepare("SELECT count(*),SUM(d8) FROM veva.product_planitem where  DocNo = ? and Dep_Code = ?");
                    $stmt6->bind_param("s", $t_Doc_No);
                    $stmt6->execute();
                    $stmt6->bind_result($p_row,$p_sumpercent);
                    $stmt6->store_result();
                    $stmt6->fetch();
                    $stmt6->close();
                    $plan_Percent = ($p_sumpercent/$p_row);
                    $stmt7 = $this->conn->prepare("UPDATE veva.product_planlist set d8 = ? WHERE DocNo = ? ");
                    $stmt7->bind_param("is", $plan_Percent,$t_Doc_No);
                    $stmt7->execute();
                    $num_affected_rows4 = $stmt7->affected_rows;
                    $stmt7->close();

                }elseif($user_department_code == '9%'){
                    $user_department_code2 = '9';
                    $stmt6 = $this->conn->prepare("SELECT count(*),SUM(d9) FROM veva.product_planitem where  DocNo = ?");
                    $stmt6->bind_param("s", $t_Doc_No);
                    $stmt6->execute();
                    $stmt6->bind_result($p_row,$p_sumpercent);
                    $stmt6->store_result();
                    $stmt6->fetch();
                    $stmt6->close();
                    $plan_Percent = ($p_sumpercent/$p_row);
                    $stmt7 = $this->conn->prepare("UPDATE veva.product_planlist set d9= ? WHERE DocNo = ? ");
                    $stmt7->bind_param("is", $plan_Percent,$t_Doc_No);
                    $stmt7->execute();
                    $num_affected_rows4 = $stmt7->affected_rows;
                    $stmt7->close();

                }elseif($user_department_code == '10%'){
                    $user_department_code2 = '10';
                    $stmt6 = $this->conn->prepare("SELECT count(*),SUM(d10) FROM veva.product_planitem where  DocNo = ?");
                    $stmt6->bind_param("s", $t_Doc_No);
                    $stmt6->execute();
                    $stmt6->bind_result($p_row,$p_sumpercent);
                    $stmt6->store_result();
                    $stmt6->fetch();
                    $stmt6->close();
                    $plan_Percent = ($p_sumpercent/$p_row);
                    $stmt7 = $this->conn->prepare("UPDATE veva.product_planlist set d10 = ? WHERE DocNo = ? ");
                    $stmt7->bind_param("is", $plan_Percent,$t_Doc_No);
                    $stmt7->execute();
                    $num_affected_rows4 = $stmt7->affected_rows;
                    $stmt7->close();

                }elseif($user_department_code == '11%'){
                    $user_department_code2 = '11';
                    $stmt6 = $this->conn->prepare("SELECT count(*),SUM(d11) FROM veva.product_planitem where  DocNo = ?");
                    $stmt6->bind_param("s", $t_Doc_No);
                    $stmt6->execute();
                    $stmt6->bind_result($p_row,$p_sumpercent);
                    $stmt6->store_result();
                    $stmt6->fetch();
                    $stmt6->close();
                    $plan_Percent = ($p_sumpercent/$p_row);
                    $stmt7 = $this->conn->prepare("UPDATE veva.product_planlist set d11 = ? WHERE DocNo = ? ");
                    $stmt7->bind_param("is", $plan_Percent,$t_Doc_No);
                    $stmt7->execute();
                    $num_affected_rows4 = $stmt7->affected_rows;
                    $stmt7->close();

                }
                
                $stmt8 = $this->conn->prepare("SELECT count(*),SUM(Percent) FROM veva.product_planitemdetail where  DocNo = ?");
                $stmt8->bind_param("s", $t_Doc_No);
                $stmt8->execute();
                $stmt8->bind_result($a_row,$a_sumpercent);
                $stmt8->store_result();
                $stmt8->fetch();
                $stmt8->close();
                
                $plan_PercentAll = ($a_sumpercent/$a_row);
                
                $stmt9 = $this->conn->prepare("UPDATE veva.product_planlist set Percent = ? WHERE DocNo = ? ");
                $stmt9->bind_param("is", $plan_PercentAll,$t_Doc_No);
                $stmt9->execute();
                $num_affected_rows5 = $stmt9->affected_rows;
                $stmt9->close();
                
            }
			
        }
        
        return $num_affected_rows3;
    }
    
    public function getTech() {
        $stmt = $this->conn->prepare("SELECT RowId,tech_name,datetime,qty FROM veva.product_planitemdetailprocess where PlanItemdetailId = ? and isactive = 1");
        $stmt->bind_param("s",$PlanItemdetailId);
        
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
        
    }
    
    public function getTech_Process($PlanItemdetailId) {
        $stmt = $this->conn->prepare("SELECT RowId,tech_name,datetime,qty FROM veva.product_planitemdetailprocess where PlanItemdetailId = ? and isactive = 1");
        $stmt->bind_param("s",$PlanItemdetailId);
        
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
        
    }
    
    public function getVersion() {
        $stmt = $this->conn->prepare("SELECT id,version FROM veva.product_plan_update order by version desc limit 1 ");
        
        $stmt->execute();
        $stmt->bind_result($id,$version);
        $stmt->store_result();
        $stmt->fetch();
        $stmt->close();
        return $version;
        
    }
    
    public function updateItemFinish($user_department_code,$PlanItemId) {
        if($user_department_code == 1){
            $stmt = $this->conn->prepare("UPDATE veva.product_planitem set  d1 = 101 WHERE PlanItemId = ?");
        }else if($user_department_code == 2){
            $stmt = $this->conn->prepare("UPDATE veva.product_planitem set  d2 = 101 WHERE PlanItemId = ?");
        }else if($user_department_code == 3){
            $stmt = $this->conn->prepare("UPDATE veva.product_planitem set  d3 = 101 WHERE PlanItemId = ?");
        }else if($user_department_code == 4){
            $stmt = $this->conn->prepare("UPDATE veva.product_planitem set  d4 = 101 WHERE PlanItemId = ?");
        }else if($user_department_code == 5){
            $stmt = $this->conn->prepare("UPDATE veva.product_planitem set  d5 = 101 WHERE PlanItemId = ?");
        }else if($user_department_code == 6){
            $stmt = $this->conn->prepare("UPDATE veva.product_planitem set  d6 = 101 WHERE PlanItemId = ?");
        }else if($user_department_code == 7){
            $stmt = $this->conn->prepare("UPDATE veva.product_planitem set  d7 = 101 WHERE PlanItemId = ?");
        }else if($user_department_code == 8){
            $stmt = $this->conn->prepare("UPDATE veva.product_planitem set  d8 = 101 WHERE PlanItemId = ?");
        }else if($user_department_code == 9){
            $stmt = $this->conn->prepare("UPDATE veva.product_planitem set  d9 = 101 WHERE PlanItemId = ?");
        }else if($user_department_code == 10){
            $stmt = $this->conn->prepare("UPDATE veva.product_planitem set  d10 = 101 WHERE PlanItemId = ?");
        }else if($user_department_code == 11){
            $stmt = $this->conn->prepare("UPDATE veva.product_planitem set  d11 = 101 WHERE PlanItemId = ?");
        }
        
        $stmt->bind_param("i",$PlanItemId);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }
    
    public function getplanlist_item_process($DocNo,$user_department_code1,$user_department_code2) {
        if($user_department_code2 == 1){
            $stmt = $this->conn->prepare(
				"SELECT  a.PlanItemId,a.DocNo,a.Item_Code,a.imgPath,a.Name1,
				(
					SELECT (product_planitem_transactions.Qty) AS Qty
					FROM product_planitem_transactions
					WHERE 	product_planitem_transactions.DocNo = a.DocNo
					AND product_planitem_transactions.Item_Code = a.Item_Code
					AND product_planitem_transactions.DepStart = ?
					AND product_planitem_transactions.Qty > 0
					ORDER BY product_planitem_transactions.RowId DESC LIMIT 1
				) AS Qty,
				a.UnitName AS Unit_Name,a.priority
				FROM veva.product_planitem a 
				Where  a.DocNo = ? and a.StepUp = 1 Order by a.Name1 ASC" // and a.d1 < 100  
			);
        }else if($user_department_code2 == 2){
            $stmt = $this->conn->prepare
			(
				"SELECT  a.PlanItemId,a.DocNo,a.Item_Code,a.imgPath,a.Name1,
				(
					SELECT (product_planitem_transactions.Qty) AS Qty
					FROM product_planitem_transactions
					WHERE 	product_planitem_transactions.DocNo = a.DocNo
					AND product_planitem_transactions.Item_Code = a.Item_Code
					AND product_planitem_transactions.DepStart = ?
					AND product_planitem_transactions.Qty > 0
					ORDER BY product_planitem_transactions.RowId DESC LIMIT 1
				) AS Qty,
				a.UnitName AS Unit_Name,a.priority
				FROM veva.product_planitem a 
				Where  a.DocNo = ? and a.StepUp = 2  Order by a.Name1 ASC"
			);
        }if($user_department_code2 == 3){
            $stmt = $this->conn->prepare(
			"SELECT  a.PlanItemId,a.DocNo,a.Item_Code,a.imgPath,a.Name1,
				(
					SELECT (product_planitem_transactions.Qty) AS Qty
					FROM product_planitem_transactions
					WHERE 	product_planitem_transactions.DocNo = a.DocNo
					AND product_planitem_transactions.Item_Code = a.Item_Code
					AND product_planitem_transactions.DepStart = ?
					AND product_planitem_transactions.Qty > 0
					ORDER BY product_planitem_transactions.RowId DESC LIMIT 1
				) AS Qty,
				a.UnitName AS Unit_Name,a.priority
				FROM veva.product_planitem a 
				Where  a.DocNo = ? and a.StepUp = 3  Order by a.Name1 ASC"
			);
        }if($user_department_code2 == 4){
            $stmt = $this->conn->prepare(
			"SELECT  a.PlanItemId,a.DocNo,a.Item_Code,a.imgPath,a.Name1,
				(
					SELECT (product_planitem_transactions.Qty) AS Qty
					FROM product_planitem_transactions
					WHERE 	product_planitem_transactions.DocNo = a.DocNo
					AND product_planitem_transactions.Item_Code = a.Item_Code
					AND product_planitem_transactions.DepStart = ?
					AND product_planitem_transactions.Qty > 0
					ORDER BY product_planitem_transactions.RowId DESC LIMIT 1
				) AS Qty,
				a.UnitName AS Unit_Name,a.priority
				FROM veva.product_planitem a 
				Where  a.DocNo = ? and a.StepUp = 4  Order by a.Name1 ASC"
			);
        }if($user_department_code2 == 5){
            $stmt = $this->conn->prepare(
			"SELECT  a.PlanItemId,a.DocNo,a.Item_Code,a.imgPath,a.Name1,
				(
					SELECT (product_planitem_transactions.Qty) AS Qty
					FROM product_planitem_transactions
					WHERE 	product_planitem_transactions.DocNo = a.DocNo
					AND product_planitem_transactions.Item_Code = a.Item_Code
					AND product_planitem_transactions.DepStart = ?
					AND product_planitem_transactions.Qty > 0
					ORDER BY product_planitem_transactions.RowId DESC LIMIT 1
				) AS Qty,
				a.UnitName AS Unit_Name,a.priority
				FROM veva.product_planitem a 
				Where  a.DocNo = ? and a.StepUp = 5  Order by a.Name1 ASC"
			);
        }if($user_department_code2 == 6){
            $stmt = $this->conn->prepare(
			"SELECT  a.PlanItemId,a.DocNo,a.Item_Code,a.imgPath,a.Name1,
				(
					SELECT (product_planitem_transactions.Qty) AS Qty
					FROM product_planitem_transactions
					WHERE 	product_planitem_transactions.DocNo = a.DocNo
					AND product_planitem_transactions.Item_Code = a.Item_Code
					AND product_planitem_transactions.DepStart = ?
					AND product_planitem_transactions.Qty > 0
					ORDER BY product_planitem_transactions.RowId DESC LIMIT 1
				) AS Qty,
				a.UnitName AS Unit_Name,a.priority
				FROM veva.product_planitem a 
				Where  a.DocNo = ? and a.StepUp = 6  Order by a.Name1 ASC"
			);
        }if($user_department_code2 == 7){
            $stmt = $this->conn->prepare(
			"SELECT  a.PlanItemId,a.DocNo,a.Item_Code,a.imgPath,a.Name1,
				(
					SELECT (product_planitem_transactions.Qty) AS Qty
					FROM product_planitem_transactions
					WHERE 	product_planitem_transactions.DocNo = a.DocNo
					AND product_planitem_transactions.Item_Code = a.Item_Code
					AND product_planitem_transactions.DepStart = ?
					AND product_planitem_transactions.Qty > 0
					ORDER BY product_planitem_transactions.RowId DESC LIMIT 1
				) AS Qty,
				a.UnitName AS Unit_Name,a.priority
				FROM veva.product_planitem a 
				Where  a.DocNo = ? and a.StepUp = 7  Order by a.Name1 ASC"
			);
        }if($user_department_code2 == 8){
            $stmt = $this->conn->prepare(
			"SELECT  a.PlanItemId,a.DocNo,a.Item_Code,a.imgPath,a.Name1,
				(
					SELECT (product_planitem_transactions.Qty) AS Qty
					FROM product_planitem_transactions
					WHERE 	product_planitem_transactions.DocNo = a.DocNo
					AND product_planitem_transactions.Item_Code = a.Item_Code
					AND product_planitem_transactions.DepStart = ?
					AND product_planitem_transactions.Qty > 0
					ORDER BY product_planitem_transactions.RowId DESC LIMIT 1
				) AS Qty,
				a.UnitName AS Unit_Name,a.priority
				FROM veva.product_planitem a 
				Where  a.DocNo = ? and a.StepUp = 8  Order by a.Name1 ASC"
			);
        }if($user_department_code2 == 9){
            $stmt = $this->conn->prepare(
			"SELECT  a.PlanItemId,a.DocNo,a.Item_Code,a.imgPath,a.Name1,
				(
					SELECT (product_planitem_transactions.Qty) AS Qty
					FROM product_planitem_transactions
					WHERE 	product_planitem_transactions.DocNo = a.DocNo
					AND product_planitem_transactions.Item_Code = a.Item_Code
					AND product_planitem_transactions.DepStart = ?
					AND product_planitem_transactions.Qty > 0
					ORDER BY product_planitem_transactions.RowId DESC LIMIT 1
				) AS Qty,
				a.UnitName AS Unit_Name,a.priority
				FROM veva.product_planitem a 
				Where  a.DocNo = ? and a.StepUp = 9  Order by a.Name1 ASC"
			);
        }if($user_department_code2 == 10){
            $stmt = $this->conn->prepare(
			"SELECT  a.PlanItemId,a.DocNo,a.Item_Code,a.imgPath,a.Name1,
				(
					SELECT (product_planitem_transactions.Qty) AS Qty
					FROM product_planitem_transactions
					WHERE 	product_planitem_transactions.DocNo = a.DocNo
					AND product_planitem_transactions.Item_Code = a.Item_Code
					AND product_planitem_transactions.DepStart = ?
					AND product_planitem_transactions.Qty > 0
					ORDER BY product_planitem_transactions.RowId DESC LIMIT 1
				) AS Qty,
				a.UnitName AS Unit_Name,a.priority
				FROM veva.product_planitem a 
				Where  a.DocNo = ? and a.StepUp = 10  Order by a.Name1 ASC"
			);
        }if($user_department_code2 == 11){
            $stmt = $this->conn->prepare(
			"SELECT  a.PlanItemId,a.DocNo,a.Item_Code,a.imgPath,a.Name1,
				(
					SELECT (product_planitem_transactions.Qty) AS Qty
					FROM product_planitem_transactions
					WHERE 	product_planitem_transactions.DocNo = a.DocNo
					AND product_planitem_transactions.Item_Code = a.Item_Code
					AND product_planitem_transactions.DepStart = ?
					AND product_planitem_transactions.Qty > 0
					ORDER BY product_planitem_transactions.RowId DESC LIMIT 1
				) AS Qty,
				a.UnitName AS Unit_Name,a.priority
				FROM veva.product_planitem a 
				Where  a.DocNo = ? and a.StepUp = 11  Order by a.Name1 ASC"
			);
        }
    
        $stmt->bind_param("ss",$user_department_code2, $DocNo);
        
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
        
    }
    
    public function insert_transaction($DocNo,$Item_Code,$DepStart,$DepEnd,$Qty,$User){
		
        $stmt = $this->conn->prepare("INSERT INTO veva.product_planitem_transactions(DocNo, Item_Code, DepStart, DepEnd, Qty,User) values(?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiiii",$DocNo,$Item_Code,$DepStart,$DepEnd,$Qty,$User);
        $result = $stmt->execute();
        if (false === $result) {
            die('execute() failed: ' . htmlspecialchars($stmt->error));
        }
        $stmt->close();
        return $result;
    }
    
    public function get_employee($section_code) {
        $stmt = $this->conn->prepare("SELECT Employee_Code,FName,LName,NName FROM veva.employee where section_code = ?");
        $stmt->bind_param("i",$section_code);
        
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
        
    }
    
    public function get_StockOut() {
        $stmt = $this->conn->prepare("SELECT wh_stock_transmit.DocNo,DATE_FORMAT(wh_stock_transmit.DocDate ,'%d-%m-%Y') AS DocDate, wh_stock_transmit.Total,wh_stock_transmit.Detail,wh_stock_transmit.RefDocNo FROM wh_stock_transmit WHERE Stock_Mode = '1' AND	wh_stock_transmit.Branch_Code = 1  AND wh_stock_transmit.Status = 3 ORDER BY wh_stock_transmit.DocNo DESC ");
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
        
    }
    
    public function get_StockOut_Detail($DocNo) {
        $stmt = $this->conn->prepare("SELECT item.NameTH,item_unit.Unit_Name,wh_stock_transmit_sub.Qty,item.Cost_Price FROM  wh_stock_transmit_sub INNER JOIN item ON item.Item_Code = wh_stock_transmit_sub.Item_Code INNER JOIN item_unit ON			item_unit.Unit_Code = item.Unit_Code WHERE	wh_stock_transmit_sub.DocNo = ?  ORDER BY wh_stock_transmit_sub.Row_No DESC");
        $stmt->bind_param("s",$DocNo);
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
        
    }
    
    public function get_employee_pin($pin) {
        $stmt = $this->conn->prepare("SELECT Employee_Code,FName,LName,NName,section_name FROM veva.employee WHERE pin = ?   ");
        $stmt->bind_param("s",$pin);
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
    }
    
    public function update_transmit($DocNo,$Employee_Code){
        $stmt = $this->conn->prepare("UPDATE veva.wh_stock_transmit set  Status = '1',Employee_Code = ? WHERE DocNo = ? ");
        $stmt->bind_param("ss",$Employee_Code,$DocNo);
        $result = $stmt->execute();
        
        if (false === $result) {
            die('execute() failed: ' . htmlspecialchars($stmt->error));
        }
        $stmt->close();
        return $result;
    }
    
	public function insert_receive($DocNo){
		$stmt = $this->conn->prepare("UPDATE veva.product_planitem set StepUp = 99 WHERE DocNo = ?");
        $stmt->bind_param("s", $DocNo);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
		//==================== 
		$tmpItem = array();
		$tmpQty = array();
		$tmpPrice = array();
		$tmpTotal = array();
		$tmpUnitCode = array();
		
		$n=1;
		$stmt = $this->conn->prepare("SELECT product_plan_detail.Item_Code,product_plan_detail.Qty,product_plan_detail.Price,product_plan_detail.Total,item.Unit_Code FROM product_plan INNER JOIN product_plan_detail ON product_plan.DocNo = product_plan_detail.DocNo INNER JOIN item ON product_plan_detail.Item_Code = item.Item_Code WHERE product_plan.DocNo = ?");

	    $stmt->bind_param("s",$DocNo);
		$stmt->execute();
		$stmt->bind_result($xItem_Code,$xQty,$xPrice,$xTotal,$xUnitCode);
			while ($stmt->fetch()) {
						$tmpItem[$n] = $xItem_Code;
						$tmpQty[$n] = $xQty;
						$tmpPrice[$n] = $xPrice;
						$tmpTotal[$n] = $xTotal;
						$tmpUnitCode[$n] = $xUnitCode;
						$n++;
			}
        $stmt->close();

		// Creat Receive	
		//==================== 	
		$stmt = $this->conn->prepare("DELETE FROM veva.xdate");
        $stmt->execute();
	    $stmt->close();	
	    $stmt = $this->conn->prepare("INSERT INTO veva.xdate ( logx ) values( DATE_FORMAT(NOW(),'%y%m') )");
        $stmt->execute();
	    $stmt->close();		
		
		
		$stmt = $this->conn->prepare("SELECT logx FROM  veva.xdate");
		$stmt->execute();
		$stmt->bind_result($xtoday);
			while ($stmt->fetch()) {
							$today = $xtoday;
			}
        $stmt->close();
		
		$Bill = "R001" . $today . "%";
		$stmt = $this->conn->prepare("SELECT lpad((SUBSTR(DocNo,-4)+1),4,'0')  AS xRun FROM wh_stock_receive WHERE DocNo LIKE ? ORDER BY DocNo DESC LIMIT 1");
		$stmt->bind_param("s",$Bill);
		$stmt->execute();
		$stmt->bind_result($xBill);
			while ($stmt->fetch()) {
							$Bill = $xBill;
			}
		$Bill = "R001" . $today .	"-" . $Bill;
        $stmt->close();

		//==================== 
			$stmt = $this->conn->prepare("INSERT INTO wh_stock_receive ( DocNo,DocDate,RefDocNo,RefDocDate,Detail,Create_Code,Create_Date,Modify_Code,Modify_Date,Branch_Code,Total,`Status`,Stock_Mode,Sup_Code,Vat,SumTotal,grv_ID,Service ) VALUES ( ?,NOW(),?,NOW(),'สินค้าที่รับเข้าจากการผลิต','9',NOW(),'9',Now(),'1','0.00','0','0','999','0','0.00','5','0' )");
			$stmt->bind_param("ss", $Bill,$DocNo);
			$stmt->execute();
			$stmt->close();

			for($i=1;$i<$n;$i++){
				$stmt = $this->conn->prepare("INSERT INTO veva.wh_stock_receive_sub  (DocNo,Item_Code,Unit_code,Qty,Qty_Adjust,
Price,IsVat,TaxCost,Productcost,Total,Row_No,Create_Code,Create_Date,Modify_Code,Modify_Date,Branch_Code,
Shelf_Code,`Status`,IO_Status,LotNo,MFGDate,EXPDate) VALUES (?,?,?,?,'0',?,0,'0','0',?,?,'9',NOW(),'9',NOW(),'1','010101',0,0,'000001',NOW(),NOW())");
				$stmt->bind_param("sssssss",$Bill,$tmpItem[$i],$tmpUnitCode[$i],$tmpQty[$i],$tmpPrice[$i],$tmpTotal[$i],$i);
				$stmt->execute();
				$stmt->close();
			}
		$stmt = $this->conn->prepare("UPDATE veva.product_planlist set status = 2 WHERE DocNo = ?");
        $stmt->bind_param("s", $DocNo);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
		//====================
			/*
			$stmt = $this->conn->prepare("INSERT INTO veva.logx ( logx ) values( ? )");
			$stmt->bind_param("s",$Bill);
			$stmt->execute();
			$stmt->close();	
			*/			
    }
	
    public function get_next_section($DocNo,$ItemCode,$DepNum) {
        do{
            $DepNum = $DepNum+1;
            $DepString = "%".$DepNum.".%";
            $stmt = $this->conn->prepare("SELECT PlanItemdetailId FROM veva.product_planitemdetail WHERE DocNo = ? and Item_Code = ? and Step LIKE ? ");
            $stmt->bind_param("sss",$DocNo,$ItemCode,$DepString);
            $stmt->execute();
            $stmt->store_result();
            $num_rows = $stmt->num_rows;
        }while($num_rows<=0 and $DepNum<=10);
        $stmt->close();
        
        //$DepNum=$DepNum+1;
        //$DepString = "%".$DepNum."%";
        return $DepNum;
    }
    
    
    

    
    /**
     * Fetching user by email
     * @param String $email User email id
     */
    public function getUserByEmail($email) {
        $stmt = $this->conn->prepare("SELECT name, email, api_key, status, created_at FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            // $user = $stmt->get_result()->fetch_assoc();
            $stmt->bind_result($name, $email, $api_key, $status, $created_at);
            $stmt->fetch();
            $user = array();
            $user["name"] = $name;
            $user["email"] = $email;
            $user["api_key"] = $api_key;
            $user["status"] = $status;
            $user["created_at"] = $created_at;
            $stmt->close();
            return $user;
        } else {
            return NULL;
        }
    }

    /**
     * Fetching user api key
     * @param String $user_id user id primary key in user table
     */
    public function getApiKeyById($user_id) {
        $stmt = $this->conn->prepare("SELECT api_key FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            // $api_key = $stmt->get_result()->fetch_assoc();
            // TODO
            $stmt->bind_result($api_key);
            $stmt->close();
            return $api_key;
        } else {
            return NULL;
        }
    }

    /**
     * Fetching user id by api key
     * @param String $api_key user api key
     */
    public function getUserId($api_key) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE api_key = ?");
        $stmt->bind_param("s", $api_key);
        if ($stmt->execute()) {
            $stmt->bind_result($user_id);
            $stmt->fetch();
            // TODO
            // $user_id = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $user_id;
        } else {
            return NULL;
        }
    }

    /**
     * Validating user api key
     * If the api key is there in db, it is a valid key
     * @param String $api_key user api key
     * @return boolean
     */
    public function isValidApiKey($api_key) {
        $stmt = $this->conn->prepare("SELECT id from users WHERE Login_Code = ?");
        $stmt->bind_param("s", $api_key);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    /**
     * Generating random Unique MD5 String for user Api key
     */
    private function generateApiKey() {
        return md5(uniqid(rand(), true));
    }

    /* ------------- `tasks` table method ------------------ */

    /**
     * Creating new task
     * @param String $user_id user id to whom task belongs to
     * @param String $task task text
     */
    public function createTask($user_id, $task) {
        $stmt = $this->conn->prepare("INSERT INTO tasks(task) VALUES(?)");
        $stmt->bind_param("s", $task);
        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            // task row created
            // now assign the task to user
            $new_task_id = $this->conn->insert_id;
            $res = $this->createUserTask($user_id, $new_task_id);
            if ($res) {
                // task created successfully
                return $new_task_id;
            } else {
                // task failed to create
                return NULL;
            }
        } else {
            // task failed to create
            return NULL;
        }
    }

    /**
     * Fetching single task
     * @param String $task_id id of the task
     */
    public function getTask($task_id, $user_id) {
        $stmt = $this->conn->prepare("SELECT t.id, t.task, t.status, t.created_at from tasks t, user_tasks ut WHERE t.id = ? AND ut.task_id = t.id AND ut.user_id = ?");
        $stmt->bind_param("ii", $task_id, $user_id);
        if ($stmt->execute()) {
            $res = array();
            $stmt->bind_result($id, $task, $status, $created_at);
            // TODO
            // $task = $stmt->get_result()->fetch_assoc();
            $stmt->fetch();
            $res["id"] = $id;
            $res["task"] = $task;
            $res["status"] = $status;
            $res["created_at"] = $created_at;
            $stmt->close();
            return $res;
        } else {
            return NULL;
        }
    }
    
    /**
     * Fetching all user tasks
     * @param String $user_id id of the user
     */
    public function getAllUserTasks($user_id) {
        $stmt = $this->conn->prepare("SELECT t.* FROM tasks t, user_tasks ut WHERE t.id = ut.task_id AND ut.user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
    }
    
    /**
     * Updating task
     * @param String $task_id id of the task
     * @param String $task task text
     * @param String $status task status
     */
    public function updateTask($user_id, $task_id, $task, $status) {
        $stmt = $this->conn->prepare("UPDATE tasks t, user_tasks ut set t.task = ?, t.status = ? WHERE t.id = ? AND t.id = ut.task_id AND ut.user_id = ?");
        $stmt->bind_param("siii", $task, $status, $task_id, $user_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    /**
     * Deleting a task
     * @param String $task_id id of the task to delete
     */
    public function deleteTask($user_id, $task_id) {
        $stmt = $this->conn->prepare("DELETE t FROM tasks t, user_tasks ut WHERE t.id = ? AND ut.task_id = t.id AND ut.user_id = ?");
        $stmt->bind_param("ii", $task_id, $user_id);
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    /* ------------- `user_tasks` table method ------------------ */

    /**
     * Function to assign a task to user
     * @param String $user_id id of the user
     * @param String $task_id id of the task
     */


}

?>
