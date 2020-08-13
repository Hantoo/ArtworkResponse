using System.Collections;
using System.Collections.Generic;
using UnityEditor;
using UnityEngine;

public class test : MonoBehaviour
{
    
    // Start is called before the first frame update
    void Start()
    {
        ArtworkResponseClient art = GetComponent<ArtworkResponseClient>();
        
        string[] data = art.insertIntoData(3, 0);
        data = art.insertIntoData(7, 1, data);
        data = art.insertIntoData("Hello Earth", 2, data);
        GetComponent<ArtworkResponseClient>().sendData(data);    
    }

    // Update is called once per frame
    void Update()
    {
        
    }
}
